var $table = $('#table')
var $remove = $('#remove')
var selections = []

function getIdSelections() {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
    return row.id
    })
}

function responseHandler(res) {
    $.each(res.rows, function (i, row) {
    row.state = $.inArray(row.id, selections) !== -1
    })
    return res
}

function detailFormatter(index, row) {
    var html = []
    $.each(row, function (key, value) {
    html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
}

function operateFormatter(value, row, index) {
    return [
    '<a class="remove" href="/admin/delete_reservation/' + row.id + '" title="Remove">',
    '<i class="fa fa-trash"></i>',
    '</a>'
    ].join('')
}

window.operateEvents = {
    'click .remove': function (e, value, row, index) {
      $table.bootstrapTable('remove', {
          field: 'id',
          values: [row.id]
      })
    }
}

function totalTextFormatter(data) {
    return 'Total'
}

function totalNameFormatter(data) {
    return data.length
}

function totalPriceFormatter(data) {
    var field = this.field
    return '$' + data.map(function (row) {
    return +row[field].substring(1)
    }).reduce(function (sum, i) {
    return sum + i
    }, 0)
}

function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
    height: 550,
    locale: "pl-PL",
    columns: [
        [{
          field: 'username',
          title: 'Użytkownik',
          sortable: true,
          align: 'center'
        }, {
          field: 'lakeName',
          title: 'Jezioro',
          sortable: true,
          align: 'center'
        }, {
          field: 'begin',
          title: 'Od',
          sortable: true,
          align: 'center'
        }, {
          field: 'end',
          title: 'Do',
          sortable: true,
          align: 'center'
        }, {
          field: 'operate',
          title: 'Operacje',
          align: 'center',
          clickToSelect: false,
          events: window.operateEvents,
          formatter: operateFormatter
        }]
    ]
    })
    $table.on('check.bs.table uncheck.bs.table ' +
    'check-all.bs.table uncheck-all.bs.table',
    function () {
    $remove.prop('disabled', !$table.bootstrapTable('getSelections').length)

    // save your data, here just save the current page
    selections = getIdSelections()
    // push or splice the selections if you want to save all data selections
    })
    $table.on('all.bs.table', function (e, name, args) {
    console.log(name, args)
    })
    $remove.click(function () {
    var ids = getIdSelections()
    $table.bootstrapTable('remove', {
        field: 'id',
        values: ids
    })
    $remove.prop('disabled', true)
    })
}

$(function() {
    initTable()
})