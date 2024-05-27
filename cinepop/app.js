const CLIENT_ID = 'TU_CLIENT_ID';
const API_KEY = 'TU_API_KEY';
const DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/drive/v3/rest"];
const SCOPES = 'https://www.googleapis.com/auth/drive.file';

function handleClientLoad() {
    gapi.load('client:auth2', initClient);
}

function initClient() {
    gapi.client.init({
        apiKey: API_KEY,
        clientId: CLIENT_ID,
        discoveryDocs: DISCOVERY_DOCS,
        scope: SCOPES
    }).then(() => {
        gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);
        updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
    }, (error) => {
        console.log(error);
    });
}

function updateSigninStatus(isSignedIn) {
    if (isSignedIn) {
        uploadFile();
    } else {
        console.log("No está autenticado");
    }
}

function handleAuthClick() {
    gapi.auth2.getAuthInstance().signIn();
}

function handleSignoutClick() {
    gapi.auth2.getAuthInstance().signOut();
}

function uploadFile() {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    if (!file) {
        alert('Selecciona un archivo primero');
        return;
    }

    const metadata = {
        'name': file.name,
        'mimeType': file.type
    };

    const form = new FormData();
    form.append('metadata', new Blob([JSON.stringify(metadata)], {type: 'application/json'}));
    form.append('file', file);

    fetch('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${gapi.auth.getToken().access_token}`
        },
        body: form
    }).then((response) => response.json()).then((data) => {
        console.log('Archivo subido', data);
    }).catch((error) => {
        console.error('Error al subir el archivo:', error);
    });
}

