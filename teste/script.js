document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contactForm');
    const contactList = document.getElementById('contactList');
    let editingContactId = null;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        let response;

        if (editingContactId) {
            formData.append('id', editingContactId);
            response = await fetch('contacts_api.php', {
                method: 'PUT',
                body: new URLSearchParams(formData)
            });
        } else {
            response = await fetch('contacts_api.php', {
                method: 'POST',
                body: formData
            });
        }

        const result = await response.json();
        alert(result.success || result.error);
        if (response.ok) {
            form.reset();
            editingContactId = null;
            loadContacts();
        } else {
            console.error('Falha ao adicionar/atualizar contato');
        }
    });

    async function loadContacts() {
        try {
            const response = await fetch('contacts_api.php');
            if (!response.ok) {
                throw new Error('Falha ao carregar contatos');
            }
            const contacts = await response.json();

            contactList.innerHTML = '';
            contacts.forEach(contact => {
                const contactDiv = document.createElement('div');
                contactDiv.className = 'contact-item';
                contactDiv.innerHTML = `
                    <div class="contact-info">
                        <strong>${contact.nome}</strong><br>
                        ${contact.telefone}<br>
                        ${contact.email}
                    </div>
                    <div class="contact-actions">
                        <button onclick="editContact(${contact.id}, '${contact.nome}', '${contact.telefone}', '${contact.email}')">Editar</button>
                        <button onclick="deleteContact(${contact.id})">Excluir</button>
                    </div>
                `;
                contactList.appendChild(contactDiv);
            });
        } catch (error) {
            console.error('Erro:', error);
        }
    }

    window.editContact = function (id, nome, telefone, email) {
        editingContactId = id;
        document.getElementById('nome').value = nome;
        document.getElementById('telefone').value = telefone;
        document.getElementById('email').value = email;
        window.scrollTo(0, 0); // Rola a tela para o formulário
    };

    window.deleteContact = async function (id) {
        if (confirm('Você tem certeza que deseja excluir este contato?')) {
            try {
                const response = await fetch('contacts_api.php', {
                    method: 'DELETE',
                    body: new URLSearchParams({ id })
                });
                const result = await response.json();
                alert(result.success || result.error);
                loadContacts();
            } catch (error) {
                console.error('Erro ao excluir contato:', error);
            }
        }
    };

    loadContacts();
});
