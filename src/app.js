let editor;
require.config({ paths: {'vs': 'node_modules/monaco-editor/min/vs' }});

require(['vs/editor/editor.main'], function() {
    editor = monaco.editor.create(document.getElementById('sql-editor-container'), {
        value: "SELECT * FROM users;",
        language: 'sql',
        theme: 'vs-light',
        automaticLayout: true,
        fontSize: 16
    });

    editor.addCommand(monaco.keyMod.CtrlCmd | monaco.KeyCode.Enter, function() {
        document.getElementById('run-btn').click();
    });
});

document.getElementById('run-btn').addEventListener('click', async () => {
    const sql = editor ? editor.getValue() : '';
    const resultDisplay = document.getElementById('result-display');
    const dbInfoDisplay = document.getElementById('db-structure-json');

    if (!sql) return;

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
            dbInfoDisplay.innerHTML = renderJsonWithTooltips(data.db_structure);
        }

    } catch (err) {
        resultDisplay.innerHTML = `<p style="color:red;">通信エラー</p>`;
    }
});

const keyDescriptions = {
    "column": "カラム(列)の名称",
    "type": "データの型(int, varchar)など",
    "nullable": "空(NULL ヌル)を許容するかどうか",
    "key": "主キー(PRI)などの制約情報"
}
function renderJsonWithTooltips(obj) {
    let jsonStr = JSON.stringify(obj, null, 4);

    return jsonStr.replace(/"(\w+)":/g, (match, key) => {
        const desc = keyDescriptions[key] || "データベースの情報です";
        return `<span class="json-key" data-tooltip="${desc}">"${key}"</span>`;
    });
}

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

function insertSQL(tableName) {
    if (editor) {
        const query = `SELECT * FROM ${tableName};`;
        editor.setValue(query);
        editor.focus();

        const lineCount = editor.getModel().getLineCount();
        editor.setPosition({ lineNumber: lineCount, column: query.length + 1 });
    }
}

window.addEventListener('load', () => {
    const dbInfoDisplay = document.getElementById('db-structure-json');
    try {
        const initialObj = JSON.parse(dbInfoDisplay.textContent);
        dbInfoDisplay.innerHTML = renderJsonWithTooltips(initialObj);
    } catch(e) { }
});