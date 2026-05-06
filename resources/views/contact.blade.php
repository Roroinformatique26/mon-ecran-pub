@extends('layouts.app')

@section('content')

<style>
  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  }

  /* CENTRAGE UNIQUEMENT DU CONTENU */
  .page-center {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: calc(100vh - 80px); /* ajuste si ta navbar change */
    padding: 20px;
  }

  .container {
    width: 100%;
    max-width: 550px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    padding: 40px;
    position: relative;
  }

  .container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 20px 20px 0 0;
  }

  h1 {
    text-align: center;
    color: #333;
    margin-bottom: 10px;
    font-size: 28px;
  }

  .subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
    font-size: 16px;
  }

  .info-planex {
    text-align: center;
    margin-bottom: 25px;
    color: #444;
    font-size: 15px;
    line-height: 1.6;
  }

  .form-group {
    margin-bottom: 20px;
  }

  label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 600;
    font-size: 14px;
  }

  select, textarea, input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 10px;
    font-size: 16px;
    transition: border-color 0.3s ease;
    background: #f8f9fa;
    font-family: inherit;
  }

  select:focus, textarea:focus, input:focus {
    outline: none;
    border-color: #667eea;
    background: white;
  }

  textarea {
    min-height: 120px;
    resize: vertical;
  }

  .btn {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    margin-top: 10px;
  }

  .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
  }

  .preview {
    margin-top: 20px;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #e1e5e9;
    white-space: pre-wrap;
    color: #333;
    font-size: 14px;
  }
</style>

<div class="page-center">
  <div class="container">

    <h1>Support Technique</h1>

    <!-- TEXTE PLANEX -->

    <p class="subtitle">
      Veuillez sélectionner le problème rencontré et fournir les détails nécessaires pour une assistance rapide.
    </p>

    <div class="form-group">
      <label for="problem">Problème rencontré ?</label>
      <select id="problem" onchange="updateIssueOptions(); updatePreview();">
        <option value="">Sélectionner un problème</option>
        <option value="Problème de connexion">Problème de connexion</option>
        <option value="Concernant le tableau des anomalies">Concernant le tableau des anomalies</option>
        <option value="Autre">Autre</option>
      </select>
    </div>

    <div class="form-group">
      <label for="issue">Sujet de la demande</label>
      <select id="issue" onchange="updatePreview()">
        <option value="">Sélectionner un sujet</option>
      </select>
    </div>

    <div class="form-group">
      <label for="details">Détails supplémentaires</label>
      <textarea id="details" placeholder="Décrivez votre problème en détail..." oninput="updatePreview()"></textarea>
    </div>

    <div class="form-group">
      <label for="phone">Votre numéro pour qu'on vous recontacte</label>
      <input type="tel" id="phone" placeholder="Ex: +33 6 12 34 56 78" oninput="updatePreview()">
    </div>

    <button class="btn" onclick="openGmail()">Ouvrir Gmail avec le mail pré-rempli</button>

    <div class="preview" id="preview">Aperçu du mail apparaîtra ici...</div>

  </div>
</div>

<script>
  const recipient = 'roro.informatique26@gmail.com';

  const issueOptions = {
    'Problème de connexion': [
      'Mot de passe oublié',
      'Email de contact oublié',
      'Nom d\'utilisateur oublié'
    ],
    'Concernant le tableau des anomalies': [
      'Problème rencontré lors de l\'utilisation',
      'Problème en ajoutant des lignes',
      'Problème d\'enregistrement',
      'Autre'
    ],
    'Autre': [
      'Question générale',
      'Bug dans l\'application',
      'Autre'
    ]
  };

  function updateIssueOptions() {
    const problem = document.getElementById('problem').value;
    const issueSelect = document.getElementById('issue');
    issueSelect.innerHTML = '<option value="">Sélectionner un sujet</option>';
    if (problem && issueOptions[problem]) {
      issueOptions[problem].forEach(option => {
        const opt = document.createElement('option');
        opt.value = option;
        opt.textContent = option;
        issueSelect.appendChild(opt);
      });
    }
  }

  function updatePreview() {
    const problem = document.getElementById('problem').value;
    const issue = document.getElementById('issue').value;
    const details = document.getElementById('details').value;
    const phone = document.getElementById('phone').value;

    let subject = 'Support Technique';
    if (problem) subject += ' - ' + problem;
    if (issue) subject += ' - ' + issue;

    let body = 'Bonjour,\n\n';
    body += 'Je rencontre le problème suivant : ' + (problem || 'non spécifié') + '.\n\n';
    if (issue) body += 'Sujet précis : ' + issue + '.\n\n';
    if (details) body += 'Détails :\n' + details + '\n\n';
    if (phone) body += 'Mon numéro de téléphone : ' + phone + '\n\n';
    body += 'Cordialement.';

    document.getElementById('preview').textContent = 'Objet : ' + subject + '\n\n' + body;
  }

  function openGmail() {
    const problem = document.getElementById('problem').value;
    const issue = document.getElementById('issue').value;
    const details = document.getElementById('details').value;
    const phone = document.getElementById('phone').value;

    let subject = 'Support Technique';
    if (problem) subject += ' - ' + problem;
    if (issue) subject += ' - ' + issue;

    let body = 'Bonjour,\n\n';
    body += 'Je rencontre le problème suivant : ' + (problem || 'non spécifié') + '.\n\n';
    if (issue) body += 'Sujet précis : ' + issue + '.\n\n';
    if (details) body += 'Détails :\n' + details + '\n\n';
    if (phone) body += 'Mon numéro de téléphone : ' + phone + '\n\n';
    body += 'Cordialement.';

    const gmailUrl = `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(recipient)}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.open(gmailUrl, '_blank');
  }

  updatePreview();
</script>

@endsection