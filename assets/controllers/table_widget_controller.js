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
    var rows = []; 
    if (typeof this.tdataValue != "undefined" && this.tdataValue.length > 0) {
      this.tdataValue.forEach(row => {
        var cols = [];
        Object.keys(this.theadValue).map((element) => {
          var value = row[element] ?? '';
          cols += `<td key=${element}>${value}</td>`;
        });
        rows += `<tr>${cols}</tr>`;
      });
    }
    rows += this.expandTable(100);
    return rows;
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
  
  expandTable(row_count)
  {
    var row_count = row_count - Object.keys(this.tdataValue).length + 1;
    var rows = [];
    var cols = [];
    var value = '&nbsp;';
    for (let i = 0; i < Object.entries(this.theadValue).length; i++)
    {
      cols += `<td>${value}</td>`;
    }

    for (let i = 0; i < row_count; i++)
    {
      rows += `<tr>${cols}</tr>`;
    }
    return rows;
  }
}