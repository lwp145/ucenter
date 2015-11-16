<!-- Bootstrap Core JavaScript -->
<script src="/admin-assets/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="/admin-assets/js/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="/admin-assets/js/jquery.dataTables.js"></script>
<script src="/admin-assets/js/dataTables.bootstrap.min.js"></script>
<!-- <script src="/sb-admin/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>-->
<!-- <script src="/sb-admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>-->

<!-- Custom Theme JavaScript -->
<script src="/admin-assets/js/sb-admin-2.js"></script>

<!-- icheck JavaScript -->
<script src="/plugin/icheck/icheck.min.js"></script>

<script src="/admin-assets/js/iat.js"></script>
<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<style>
td.highlight {
    background-color: whitesmoke !important;
}
.table-bordered thead tr td {
	border-bottom-width: 0px;
	// text-align: center;
}
div.dataTables_length {
	padding-left: 15px;
	padding-top: 4px;
	color: #777;
}
div.dataTables_info {
	padding-top: 8px;
	color: #777;
}
.JColResizer {
	 // display:none;
	 // margin:0,4px,0,4px !important;
}
</style>
<script>
$('input').iCheck({
	checkboxClass: 'icheckbox_square-blue',
	radioClass: 'iradio_square-red',
	increaseArea: '20%' // optional
});
function datatable_base() {
	$.extend( $.fn.dataTable.defaults, {
			"dom":
				"<'row'<'col-sm-6'><'col-sm-6'>r>"+
				"t"+
				"<'row'<'pull-left'l><'pull-left'i><'col-sm-6 pull-right'p>>",
			"pagingType": "full_numbers",
			"iDisplayLength": 8,
			"lengthMenu": [[8, 25, 50, 100], [8, 25, 50, 100]],
			"language": {
				"processing" : "<img src='/images/loading.gif'>",
				"lengthMenu": "每页 _MENU_ 条 ",
				"zeroRecords": "没有找到记录",
				"info": "，共 _PAGES_ 页，共 _TOTAL_ 条",
				"infoEmpty": "无记录",
				"infoFiltered": "(从 _MAX_ 条记录过滤)",
				"search":"搜索：",
				"loadingRecords": "载入中...",
				"paginate":{
					"first":"首页",
					"previous":"上一页",
					"next":"下一页",
					"last":"尾页"
				}
			},
			"processing": true,
			"serverSide": true,
            "responsive": true,
			'stateSave': true,
			"stateLoaded": function (settings, data) {
				$("#search").val(data.search.search);
			}
	} );
}
if(typeof(datatable_id) != "undefined") {
var table;
$(document).ready(function() {
	datatable_base();
    table = $('#' + datatable_id).DataTable({
		//禁用排序列
		"columnDefs": [{
			"orderable": false,
			"targets": columnDefs_targets
		}],
		//默认排序列
		"order": order,
		"ajax": {
			"url": ajax_url,
			"type": 'POST',
			"dataType": 'jsonp',
			"headers": {
				'X-CSRF-TOKEN': $('input[name="_token"]').val()
			},
		},
		"columns": columns,
		"initComplete": initComplete
	});
});
}
</script>
