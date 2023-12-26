import { Controller } from '@hotwired/stimulus';
import { React } from 'react';

const queryParameters = new URLSearchParams(window.location.search);
const direction = queryParameters.get('direction');
const sort = queryParameters.get('sort');


export default class extends Controller {
  static values = { thead: Object, tdata: Array }

  connect() {
    this.element.innerHTML = `<table><thead><tr>${this.createTHead()}</tr></thead><tbody>${this.createTBody()}</tbody></table>`;
  }

  createTHead() {
    if (typeof this.theadValue != "undefined" && Object.entries(this.theadValue).length > 0) {
      var cols = [];
      Object.entries(this.theadValue).map((value) => {
        cols += `<th key="${value[0]}" class="sortable asc"><a href="${window.location.pathname}?sort=${value[0]}&direction=${this.sortDirection(value[0], direction ?? 'asc')}">${value[1]}</a></th>`;
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

  sortDirection(value, direction) {
    // if key value is already selected and direction is asc, make it desc.  Otherwise make it asc.
    if (value == sort && direction == 'asc') {
      direction = 'desc';
    } else {
      direction = 'asc';
    }

    return direction;
  }
}