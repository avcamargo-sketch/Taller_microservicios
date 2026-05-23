const msgModal = document.getElementById('msgModal');
const msgText = document.getElementById('msgText');

const showMsg = (text = 'Datos guardados!!!') => {
    msgText.textContent = text;
    msgModal.classList.remove('close');
}

const hideMsg = () => {
    msgModal.classList.add('close');
}

const showForm = (formId) => {
    document.getElementById(formId).classList.remove('close');
}

const hideForm = (formId) => {
    document.getElementById(formId).classList.add('close');
}

const mostrarTab = (tab) => {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('btn-' + tab).classList.add('active');
    
    if (tab === 'informes') {
        cargarInformes();
    }
}