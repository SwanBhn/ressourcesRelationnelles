import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';





document.addEventListener('DOMContentLoaded', () => {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const saveButton = document.getElementById('save-button');

    let initialName = nameInput.value;
    let initialEmail = emailInput.value;

    function checkChanges() {
        console.log("ici")
        if (nameInput.value !== initialName || emailInput.value !== initialEmail) {
            saveButton.disabled = false;
        } else {
            saveButton.disabled = true;
        }
    }

    nameInput.addEventListener('input', checkChanges);
    emailInput.addEventListener('input', checkChanges);

    saveButton.addEventListener('click', async () => {
        const name = nameInput.value;
        const email = emailInput.value;

        if (!name.trim() || !email.trim()) {
            alert("Veuillez remplir tous les champs.");
            return;
        }

        const response = await fetch('/api/user/update', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, email })
        });

        const result = await response.json();

        if (response.ok) {
            alert(result.message);
            // Mise à jour des valeurs initiales après une sauvegarde réussie
            initialName = name;
            initialEmail = email;
            saveButton.disabled = true;
            window.location.reload();
        } else {
            alert('Erreur : ' + result.message);
        }
    });
});


