document.getElementById('run-btn').addEventListener('click', async () => {
    const sql = document.getElementById('sql-editor').value;
    const resultDisplay = document.getElementById('result-display');
    const dbInfoDisplay = document.getElementById('db-structure-json');

    try {
        const response = await fetch('execute.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ sql: sql })
        });

        const data = await response.json();

        if (data.error) {
            resultDisplay.innerHTML = `<p style="color:red;">Error: ${data.error}</p>`;
        } else {
            renderTable(data.result);
        }

        dbInfoDisplay.textContent = JSON.stringify(data.db_structure, null, 4);

    } catch (err) {
        resultDisplay.innerHTML = `<p style="color:red;">通信エラー</p>`;
    }
});

function renderTable(rows) {
    if (!rows || rows.length === 0) {
        document.getElementById('result-display').innerHTML = "No data found or Query executed successfully.";
        return;
    }
    let html = '<table><thead><tr>';
    Object.keys(rows[0]).forEach(key => html += `<th>${key}</th>`);
    html += '</tr></thead><tbody>';
    rows.forEach(row => {
        html += '<tr>';
        Object.values(row).forEach(val => html += `<td>${val}</td>`);
        html += '</tr>';
    });
    html += '</tbody></table>';
    document.getElementById('result-display').innerHTML = html;
}