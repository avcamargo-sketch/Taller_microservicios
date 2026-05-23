const API_URL = 'http://127.0.0.1:8000';
const sprints = [];
const sprintsTable = document.getElementById('sprintsTb');
const sprintForm = document.forms['sprintForm'];
let sprint_id = null;

const consultarSprints = async () => {
    try {
        if (sprints.length > 0) sprints.splice(0, sprints.length);
        
        const response = await fetch(`${API_URL}/api/sprints`);
        const body = await response.json();
        
        body.forEach((item) => {
            sprints.push({
                id: item.id,
                nombre: item.nombre,
                fecha_inicio: item.fecha_inicio,
                fecha_fin: item.fecha_fin,
            });
        });
        
        mostrarListaSprints();
        actualizarSelectSprints();
    } catch (error) {
        console.error("Error en consultar los sprints");
    }
};

const mostrarListaSprints = () => {
    const tbody = sprintsTable.getElementsByTagName('tbody')[0];
    tbody.innerHTML = '';
    
    if (sprints.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5">No hay datos...</td></tr>';
        return;
    }
    
    for (let item of sprints) {
        const tr = document.createElement('tr');
        
        const nombreTd = document.createElement('td');
        nombreTd.textContent = item.nombre;
        
        const inicioTd = document.createElement('td');
        inicioTd.textContent = item.fecha_inicio;
        
        const finTd = document.createElement('td');
        finTd.textContent = item.fecha_fin;
        
        const countTd = document.createElement('td');
        countTd.textContent = '0'; // Se actualizará con historias
        
        const accionesTd = document.createElement('td');
        
        const editarBtn = document.createElement('button');
        editarBtn.textContent = 'Editar';
        editarBtn.addEventListener('click', () => modificarSprint(item));
        
        const eliminarBtn = document.createElement('button');
        eliminarBtn.textContent = 'Eliminar';
        eliminarBtn.addEventListener('click', () => eliminarSprint(item.id));
        
        accionesTd.appendChild(editarBtn);
        accionesTd.appendChild(eliminarBtn);
        
        tr.appendChild(nombreTd);
        tr.appendChild(inicioTd);
        tr.appendChild(finTd);
        tr.appendChild(countTd);
        tr.appendChild(accionesTd);
        
        tbody.appendChild(tr);
    }
};

const modificarSprint = (item) => {
    sprint_id = item.id;
    sprintForm['nombre'].value = item.nombre;
    sprintForm['fecha_inicio'].value = item.fecha_inicio;
    sprintForm['fecha_fin'].value = item.fecha_fin;
    showForm('sprintForm');
};

const registrarSprint = async () => {
    try {
        const sprint = {
            nombre: sprintForm['nombre'].value,
            fecha_inicio: sprintForm['fecha_inicio'].value,
            fecha_fin: sprintForm['fecha_fin'].value
        };
        
        const response = await fetch(`${API_URL}/api/sprints`, {
            headers: { 'Content-Type': 'application/json' },
            method: 'post',
            body: JSON.stringify(sprint)
        });
        
        const body = await response.json();
        const status = response.status;
        
        if (status === 201) {
            showMsg('Sprint guardado!!!');
            sprints.push({
                id: body.id,
                nombre: body.nombre,
                fecha_inicio: body.fecha_inicio,
                fecha_fin: body.fecha_fin,
            });
            mostrarListaSprints();
            actualizarSelectSprints();
            sprintForm.reset();
            hideForm('sprintForm');
        }
    } catch (error) {
        console.error("Error al guardar el sprint");
    }
};

const editarSprint = async () => {
    try {
        const sprint = {
            nombre: sprintForm['nombre'].value,
            fecha_inicio: sprintForm['fecha_inicio'].value,
            fecha_fin: sprintForm['fecha_fin'].value
        };
        
        const response = await fetch(`${API_URL}/api/sprints/${sprint_id}`, {
            headers: { 'Content-Type': 'application/json' },
            method: 'put',
            body: JSON.stringify(sprint)
        });
        
        const status = response.status;
        
        if (status === 200) {
            showMsg('Sprint actualizado!!!');
            consultarSprints();
            sprint_id = null;
            sprintForm.reset();
            hideForm('sprintForm');
        }
    } catch (error) {
        console.error("Error al actualizar el sprint");
    }
};

const eliminarSprint = async (id) => {
    if (!confirm('¿Eliminar este sprint? Se eliminarán todas sus historias.')) return;
    
    try {
        const response = await fetch(`${API_URL}/api/sprints/${id}`, {
            method: 'delete'
        });
        
        const status = response.status;
        
        if (status === 200) {
            showMsg('Sprint eliminado!!!');
            consultarSprints();
        }
    } catch (error) {
        console.error("Error al borrar el sprint");
    }
};

sprintForm.addEventListener('submit', (event) => {
    event.preventDefault();
    sprint_id ? editarSprint() : registrarSprint();
});

sprintForm.addEventListener('reset', (event) => {
    sprint_id = null;
});

consultarSprints();