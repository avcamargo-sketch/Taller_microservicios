const cargarInformes = async () => {
    try {
        const [resSprints, resResp] = await Promise.all([
            fetch(`${API_URL}/api/informes/sprints`),
            fetch(`${API_URL}/api/informes/responsables`)
        ]);
        
        const informeSprints = await resSprints.json();
        const informeResp = await resResp.json();
        
        mostrarInformeSprints(informeSprints);
        mostrarInformeResponsables(informeResp);
    } catch (error) {
        console.error("Error cargando informes");
    }
};

const mostrarInformeSprints = (datos) => {
    const tbody = document.getElementById('informeSprintsTb').getElementsByTagName('tbody')[0];
    tbody.innerHTML = '';
    
    if (datos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6">No hay datos...</td></tr>';
        return;
    }
    
    for (let d of datos) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${d.sprint_nombre}</td>
            <td>${d.total_historias}</td>
            <td><span class="estado estado-finalizada">${d.finalizadas}</span></td>
            <td><span class="estado estado-nueva">${d.pendientes}</span></td>
            <td><span class="estado estado-impedimento">${d.impedimentos}</span></td>
            <td>${d.puntos_totales}</td>
        `;
        tbody.appendChild(tr);
    }
};

const mostrarInformeResponsables = (datos) => {
    const tbody = document.getElementById('informeRespTb').getElementsByTagName('tbody')[0];
    tbody.innerHTML = '';
    
    if (datos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6">No hay datos...</td></tr>';
        return;
    }
    
    for (let d of datos) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><strong>${d.responsable}</strong></td>
            <td>${d.total_historias}</td>
            <td><span class="estado estado-finalizada">${d.finalizadas}</span></td>
            <td><span class="estado estado-nueva">${d.pendientes}</span></td>
            <td><span class="estado estado-impedimento">${d.impedimentos}</span></td>
            <td>${d.puntos_totales}</td>
        `;
        tbody.appendChild(tr);
    }
};