<template>
    <div class="box box-primary">
        <div class="box-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-hover table-condensed" style="width:100%;">
                    <thead>
                        <tr>
                            <th v-for="(column, idx) in columns">
                                {{column}}
                            </th>
                        </tr>
                    </thead>
                    <tfoot v-if="! isUnique">
                        <tr>
                            <th v-for="(column, idx) in columns">
                                <div v-if=" idx != columns.length -1" contenteditable="true" :placeholder="column" class="input-dt"></div>
                            </th>
                        </tr>
                    </tfoot>
                    <tbody>                   
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style type="text/css">
    tfoot {
        display: table-header-group;
    }

    [contenteditable=true]:empty:before{
      content: attr(placeholder);
      display: block; /* For Firefox */
      color: #E0E0E0;
    }
    .input-dt{
        border:1px solid #d2d6de;
        padding: 3px;
    }

    .input-dt:focus{
        border:1px solid #3c8dbc;
    }
</style>
<script>
    import pdfMake from "pdfmake/build/pdfmake";
    import pdfFonts from "pdfmake/build/vfs_fonts";
    pdfMake.vfs = pdfFonts.pdfMake.vfs;

    import 'datatables.net/js/jquery.dataTables.js';
    
    import 'datatables.net-bs/css/dataTables.bootstrap.css';
    import 'datatables.net-bs/js/dataTables.bootstrap.js';    
    
    import 'datatables.net-buttons/js/dataTables.buttons.js';
    import 'datatables.net-buttons/js/buttons.colVis.js'; //# Column visibility
    import 'datatables.net-buttons/js/buttons.html5.js';  //# HTML 5 file export
    import 'datatables.net-buttons/js/buttons.flash.js';  //# Flash file export
    import 'datatables.net-buttons/js/buttons.print.js';  //# Print view button

    import 'datatables.net-buttons-bs/css/buttons.bootstrap.css';
    import 'datatables.net-buttons-bs/js/buttons.bootstrap.js';

    //import 'datatables.net-responsive-bs/css/responsive.bootstrap.css';
    //import 'datatables.net-responsive-bs/js/responsive.bootstrap.js';

    export default {
        data: function () {
            return {
            }
        },
        props: {
            columns: Array,
            url: String,
            isUnique: {
                default: false,
                type: Boolean
            }
        },
        mounted() {
            console.log('Component mounted.')
            var that= this;

            var settings = {
                ajax: that.url,
                dom: 'rt',
                columnDefs: [
                    { "orderable": false, "targets": [-1] }
                ]
                //responsive: true                
            };

            if(! this.isUnique){
                settings['buttons'] = [
                    'colvis',
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ];

                settings["dom"]= 'lBfrtip';
            }

            var table= $('#dataTable').DataTable(settings);

            if(! this.isUnique){
                table.columns().every( function () {
                    var that = this;
             
                    $( 'div', this.footer() ).on( 'keyup change', function () {
                        if ( that.search() !== $(this).text() ) {
                            that
                                .search( $(this).text() )
                                .draw();
                        }
                    } );
                } );
            }
        }
    }
</script>