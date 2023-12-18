import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
  static values = { thead: Object, tdata: Array }

  connect() {
    console.log(JSON.stringify(this.tdataValue));
    this.element.innerHTML = `<table><thead><tr>${this.createTHead()}</tr></thead><tbody>${this.createTBody()}</tbody></table>`;
  }

  createTHead() {
    if (typeof this.theadValue != "undefined" && Object.entries(this.theadValue).length > 0) {
      var cols = [];
      Object.entries(this.theadValue).map((value) => {
        cols += `<th key=${value[0]}>${value[1]}</th>`;
      })
      return cols;
    }
  }

  createTBody() {
    if (typeof this.tdataValue != "undefined" && this.tdataValue.length > 0) {
      var rows = [];
      this.tdataValue.forEach(row => {
        var cols = [];
        Object.keys(this.theadValue).map((element) => {
          cols += `<td key=${element}>${row[element]}</td>`;
        });
        rows += `<tr>${cols}</tr>`;
      });
      return rows;
    }
  }
}