var Table = class Table {

    constructor(tableId) {
        this.table = document.getElementById(tableId);
    }

    newRow() {
        const table = this.table;
        const templateRow = table.rows[table.rows.length - 1];
        const newRow = table.insertRow(-1);
        for (const cell of templateRow.cells) {
            const newCell = newRow.insertCell(-1);
            Array.from(cell.attributes).forEach(node =>
                newCell.setAttributeNode(node.cloneNode(true))
            );
            newCell.innerHTML = cell.innerHTML;
        }
        this.#updateArgsIndex(table);
    }

    deleteRow(childElement) {
        const table = this.table;
        const rows = Array.from(table.rows).slice(1); //Ignore Header Row
        if(rows.length <= 1) {
            console.log('Tried to delete last row, which is required for cloning');
            return;
        }
        const row = rows.filter(r => r.contains(childElement));
        if(row[0]) {
            row[0].remove();
        }
        this.#updateArgsIndex(table);
    }

    #updateArgsIndex() {
        const table = this.table;
        let index = 0;
        for(const row of table.rows) {
            const inputs = row.getElementsByTagName('input');
            for(const input of inputs) {
                const name = input.attributes['name'].nodeValue;
                const newName = name.replace(/\d+/gm, index);
                input.attributes['name'].nodeValue = newName;
            }
        }
        index++;
    }
}
