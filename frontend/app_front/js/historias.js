const historias = [];
const historiasTable = document.getElementById('historiasTb');
const historiaForm = document.forms['historiaForm'];
let historia_id = null;

const consultarHistorias = async () => {
    try {
        if (historias.length > 0) historias.splice(0, historias.length);
        
        const response = await fetch(`${API_URL}/api/historias`);
        const body = await response.json();
        
        body.forEach((item) => {
            historias.push({
                id: item.id,
                titulo: item.titulo,
                descripcion: item.descripcion,
                responsable: item.responsable,
                estado: item.estado,
                puntos: item.puntos,
                fecha_creacion: item.fecha_creacion,
                fecha_finalizacion: item.fecha_finalizacion,
                sprint_id: item.sprint_id,
            });
        });
        
        mostrarListaHistorias();
    } catch (error) {
        console.error("Error en consultar las historias");
    }
};

const actualizarSelectSprints = () => {
    const selects = document.querySelectorAll('select[name="sprint_id"], #filtro-sprint');
    selects.forEach(select => {
        const actual = select.value;
        select.innerHTML = '<option value="">Selecciona...</option>';
        sprints.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.nombre;
            select.appendChild(opt);
        });
        if (actual) select.value = actual;
    });
};

const mostrarListaHistorias = (filtradas = null) => {
    const tbody = historiasTable.getElementsByTagName('tbody')[0];
    tbody.innerHTML = '';
    
    const datos = filtradas || historias;
    
    if (datos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6">No hay datos...</td></tr>';
        return;
    }
    
    for (let item of datos) {
        const sprint = sprints.find(s => s.id == item.sprint_id);
        
        const tr = document.createElement('tr');
        
        const tituloTd = document.createElement('td');
        tituloTd.innerHTML = `<strong>${item.titulo}</strong><br><small>${item.descripcion.substring(0, 30)}...</small>`;
        
        const respTd = document.createElement('td');
        respTd.textContent = item.responsable;
        
        const sprintTd = document.createElement('td');
        sprintTd.textContent = sprint ? sprint.nombre : 'N/A';
        
        const puntosTd = document.createElement('td');
        puntosTd.textContent = item.puntos;
        
        const estadoTd = document.createElement('td');
        estadoTd.innerHTML = `<span class="estado estado-${item.estado}">${item.estado}</span>`;
        
        const accionesTd = document.createElement('td');
        
        const editarBtn = document.createElement('button');
        editarBtn.textContent = 'Editar';
        editarBtn.addEventListener('click', () => modificarHistoria(item));
        
        const eliminarBtn = document.createElement('button');
        eliminarBtn.textContent = 'Eliminar';
        eliminarBtn.addEventListener('click', () => eliminarHistoria(item.id));
        
        accionesTd.appendChild(editarBtn);
        accionesTd.appendChild(eliminarBtn);
        
        tr.appendChild(tituloTd);
        tr.appendChild(respTd);
        tr.appendChild(sprintTd);
        tr.appendChild(puntosTd);
        tr.appendChild(estadoTd);
        tr.appendChild(accionesTd);
        
        tbody.appendChild(tr);
    }
};

const modificarHistoria = (item) => {
    historia_id = item.id;
    historiaForm['titulo'].value = item.titulo;
    historiaForm['descripcion'].value = item.descripcion;
    historiaForm['responsable'].value = item.responsable;
    historiaForm['puntos'].value = item.puntos;
    historiaForm['sprint_id'].value = item.sprint_id;
    historiaForm['estado'].value = item.estado;
    historiaForm['fecha_creacion'].value = item.fecha_creacion;
    historiaForm['fecha_finalizacion'].value = item.fecha_finalizacion || '';
    showForm('historiaForm');
};

const registrarHistoria = async () => {
    try {
        const historia = {
            titulo: historiaForm['titulo'].value,
            descripcion: historiaForm['descripcion'].value,
            responsable: historiaForm['responsable'].value,
            puntos: parseInt(historiaForm['puntos'].value),
            sprint_id: parseInt(historiaForm['sprint_id'].value),
            estado: historiaForm['estado'].value,
            fecha_creacion: historiaForm['fecha_creacion'].value,
            fecha_finalizacion: historiaForm['fecha_finalizacion'].value || null
        };
        
        const response = await fetch(`${API_URL}/api/historias`, {
            headers: { 'Content-Type': 'application/json' },
            method: 'post',
            body: JSON.stringify(historia)
        });
        
        const body = await response.json();
        const status = response.status;
        
        if (status === 201) {
            showMsg('Historia guardada!!!');
            consultarHistorias();
            historiaForm.reset();
            hideForm('historiaForm');
        }
    } catch (error) {
        console.error("Error al guardar la historia");
    }
};

const editarHistoria = async () => {
    try {
        const historia = {
            titulo: historiaForm['titulo'].value,
            descripcion: historiaForm['descripcion'].value,
            responsable: historiaForm['responsable'].value,
            puntos: parseInt(historiaForm['puntos'].value),
            sprint_id: parseInt(historiaForm['sprint_id'].value),
            estado: historiaForm['estado'].value,
            fecha_creacion: historiaForm['fecha_creacion'].value,
            fecha_finalizacion: historiaForm['fecha_finalizacion'].value || null
        };
        
        const response = await fetch(`${API_URL}/api/historias/${historia_id}`, {
            headers: { 'Content-Type': 'application/json' },
            method: 'put',
            body: JSON.stringify(historia)
        });
        
        const status = response.status;
        
        if (status === 200) {
            showMsg('Historia actualizada!!!');
            consultarHistorias();
            historia_id = null;
            historiaForm.reset();
            hideForm('historiaForm');
        }
    } catch (error) {
        console.error("Error al actualizar la historia");
    }
};

const eliminarHistoria = async (id) => {
    if (!confirm('¿Eliminar esta historia?')) return;
    
    try {
        const response = await fetch(`${API_URL}/api/historias/${id}`, {
            method: 'delete'
        });
        
        const status = response.status;
        
        if (status === 200) {
            showMsg('Historia eliminada!!!');
            consultarHistorias();
        }
    } catch (error) {
        console.error("Error al borrar la historia");
    }
};

const filtrarHistorias = () => {
    const sprintId = document.getElementById('filtro-sprint').value;
    const estado = document.getElementById('filtro-estado').value;
    
    let filtradas = historias;
    
    if (sprintId) {
        filtradas = filtradas.filter(h => h.sprint_id == sprintId);
    }
    
    if (estado) {
        filtradas = filtradas.filter(h => h.estado === estado);
    }
    
    mostrarListaHistorias(filtradas);
};

historiaForm.addEventListener('submit', (event) => {
    event.preventDefault();
    historia_id ? editarHistoria() : registrarHistoria();
});

historiaForm.addEventListener('reset', (event) => {
    historia_id = null;
});