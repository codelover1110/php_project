<?php 
    include '../../layout/header.php'; 
?>
<nav class="navbar navbar-expand-sm bg-light">
  <ul class="tabs">
    <li class="nav-item active active">
      <a class="nav-link" href="/frequency/pages/discovery/">DISCOVERY</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/frequency/pages/modulations/">MODULATIONS</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/frequency/pages/groups/">GROUPS</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/frequency/pages/antennas/">ANTENNAS</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/frequency/pages/discoverers/">DISCOVERERS</a>
    </li>
  </ul>
</nav>

<br>


<script type="text/javascript" language="javascript" class="init">


/* Formatting function for row details - modify as you need */
function format ( d ) {
	// `d` is the original data object for the row
	return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
		'<tr>'+
			'<td>Note:</td>'+
			'<td>'+d.note+'</td>'+
		'</tr>'
	'</table>';
}

var editor;

$(document).ready(function() {

	var table = $('#discovery_table').DataTable( {
		dom: "Bfrtip",
		ajax: "./staff.php",
		"columns": [
			{
				"className":      'details-control',
				"orderable":      false,
				"data":           null,
				"defaultContent": ''
			},
			{ data: "id", },
			{ data: "date_time"},
			{ data: "frequency"},
			{ data: "modulation_id"},
			{ data: "group_id"},
			{ data: "antenna_id"},
			{ data: "signal"},
			{ data: "location_name"},
			{ data: "discoverer_id"},
		],
		"order": [[1, 'asc']],
		select: true,
		buttons: [
			{ extend: 'collection',
			  text: 'Export',
			  buttons: [
							'copy',
							'excel',
							'csv',
							'pdf',
							'print'
						]
					}
				]
	});
	
	// Add event listener for opening and closing details
	$('#discovery_table tbody').on('click', 'td.details-control', function () {
		var tr = $(this).closest('tr');
		var row = table.row( tr );

		if ( row.child.isShown() ) {
			// This row is already open - close it
			row.child.hide();
			tr.removeClass('shown');
		}
		else {
			// Open this row
			row.child( format(row.data()) ).show();
			tr.addClass('shown');
		}
	} );


	// Add new discovery data

	$('#addButton').on('click', function () {
		var modulation_select = $('#modulation');
		var antenna_select = $('#antenna');
		var discoverer_select = $('#discoverer');
		var group_select = $('#group');
		var response;
		
		$.ajax('./staff.php', {
			type: 'POST',  // http method
			data: { flag: 'get_addModalData' },  // data to submit
			success: function (data) {
				response = JSON.parse(data);
				var i;
				for (i = 0; i < response.modulation.length; i++) {
					modulation_select.append("<option value="+response.modulation[i]["id"]+">"+response.modulation[i]['modulation']+"</option>")
				}
				for (i = 0; i < response.antenna.length; i++) {
					antenna_select.append("<option value="+response.antenna[i]["id"]+">"+response.antenna[i]['antenna']+"</option>")
				}
				for (i = 0; i < response.discoverer.length; i++) {
					discoverer_select.append("<option value="+response.discoverer[i]["id"]+">"+response.discoverer[i]['discoverer']+"</option>")
				}
				for (i = 0; i < response.group.length; i++) {
					group_select.append("<option value="+response.group[i]["id"]+">"+response.group[i]['group_name']+"</option>")
				}
				$('#addModal').modal('show');

			},
			error: function (errorMessage) {
			}
		});
	});

	$('#saveButton').on('click', function () {
		var frequency = $('#frequency').val();
		var group = $('#group').val();
		var antenna = $('#antenna').val();
		var discoverer = $('#discoverer').val();
		var signal = $('#signal').val();
		var location_name = $('#location_name').val();
		var note = $('#note').val();
		var modulation = $('#modulation').val();

		if (modulation && frequency && modulation && group && antenna && discoverer && location_name && note ) {
			if ((frequency - 1)) {
				$.ajax('./staff.php', {
					type: 'POST',  // http method
					data: { flag: 'add_modalData',
							frequency: frequency,
							group: group,
							antenna: antenna,
							discoverer: discoverer,
							signal: signal,
							location_name: location_name,
							note: note,
							modulation: modulation
					},  // data to submit
					success: function (data) {
						console.log(data)
						$('#signal').val("");
						$('#location_name').val("");
						$('#note').val("");
						$('#frequency').val("");
						$('#addModal').modal('hide');
						location.reload();

					},
					error: function (errorMessage) {
					}
				});
			} else {
				alert("Input number in frequency value.")
			}
		} else {
			alert("You have to insert value correctly. Try again.")
		}
		

	});

	$('#updateData').on('click', function () {
		var frequency = $('#frequency1').val();
		var group = $('#group1').val();
		var antenna = $('#antenna1').val();
		var discoverer = $('#discoverer1').val();
		var signal = $('#signal1').val();
		var location_name = $('#location_name1').val();
		var note = $('#note1').val();
		var modulation = $('#modulation1').val();
		var discovery_id = $('#discovery_id').val();

		if (modulation && frequency && modulation && group && antenna && discoverer && location_name && note ) {
			if ((frequency - 1)) {
				$.ajax('./staff.php', {
					type: 'POST',  // http method
					data: { flag: 'update_modalData',
							frequency: frequency,
							group: group,
							antenna: antenna,
							discoverer: discoverer,
							signal: signal,
							location_name: location_name,
							note: note,
							modulation: modulation,
							discovery_id: discovery_id
					},  // data to submit
					success: function (data) {
						$('#editModal').modal('hide');
						location.reload();
					},
					error: function (errorMessage) {
					}
				});
			} else {
				alert("Input number in frequency value.")
			}
		} else {
			alert("You have to insert value correctly. Try again.")
		}
		

	});

	$('#editButton').on('click', function (){
		if($("tbody").find('.selected')[0]) {
			var selectedElement = $("tbody").find('.selected')[0]['cells'][1];
			var selected_id = $(selectedElement).text();
			var date_time = $($("tbody").find('.selected')[0]['cells'][2]).text()
			var frequency = $($("tbody").find('.selected')[0]['cells'][3]).text().replace("\.", "")
			var modulation = $($("tbody").find('.selected')[0]['cells'][4]).text()
			var group = $($("tbody").find('.selected')[0]['cells'][5]).text()
			var antenna = $($("tbody").find('.selected')[0]['cells'][6]).text()
			var signal = $($("tbody").find('.selected')[0]['cells'][7]).text()
			var location = $($("tbody").find('.selected')[0]['cells'][8]).text()
			var discoverer = $($("tbody").find('.selected')[0]['cells'][9]).text()
			var note
			var modulation_select = $('#modulation1');
			var antenna_select = $('#antenna1');
			var discoverer_select = $('#discoverer1');
			var group_select = $('#group1');
			var response;
		
			$.ajax('./staff.php', {
				type: 'POST',  // http method
				data: { flag: 'get_editModalData', discovery_id: selected_id },  // data to submit
				success: function (data) {
					response = JSON.parse(data);
					var i;
					for (i = 0; i < response.modulation.length; i++) {
						var selected = "";
						if(modulation == response.modulation[i]['modulation'])
							selected = "selected"
						modulation_select.append("<option value="+response.modulation[i]["id"]+' '+selected+">"+response.modulation[i]['modulation']+"</option>")
					}
					for (i = 0; i < response.antenna.length; i++) {
						var selected = "";
						if(antenna == response.antenna[i]['antenna'])
							selected = "selected"
						antenna_select.append("<option value="+response.antenna[i]["id"]+' '+selected+">"+response.antenna[i]['antenna']+"</option>")
					}
					for (i = 0; i < response.discoverer.length; i++) {
						var selected = "";
						if(discoverer == response.discoverer[i]['discoverer'])
							selected = "selected"
						discoverer_select.append("<option value="+response.discoverer[i]["id"]+' '+selected+">"+response.discoverer[i]['discoverer']+"</option>")
					}
					for (i = 0; i < response.group.length; i++) {
						var selected = "";
						if(group == response.group[i]['group_name'])
							selected = "selected"
						group_select.append("<option value="+response.group[i]["id"]+' '+selected+">"+response.group[i]['group_name']+"</option>")
					}
					note = response.discovery[0]["note"]
					console.log(response)
					$('#frequency1').val(frequency);
					$('#discovery_id').val(selected_id);
					$('#signal1').val(signal);
					$('#location_name1').val(location);
					$('#note1').val(note);
					$('#editModal').modal('show');

					},
					error: function (errorMessage) {
				}
			});
				
		} else {
			alert("Select the line!")
		}
	});

	$('#deleteButton').on('click', function () {
		if($("tbody").find('.selected')[0]) {
			var r = confirm("Do you really delete this line?");
			if (r == true) {
				var selectedElement = $("tbody").find('.selected')[0]['cells'][1];
				var selected_id = $(selectedElement).text();
				$.ajax('./staff.php', {
					type: 'POST',  // http method
					data: { delete_id: selected_id,
							flag: 'delete_modalData'
					},  // data to submit
					success: function (data) {
						$("tbody").find('.selected').remove();
						location.reload();
					},
					error: function (errorMessage) {
					}
				});
				
			}
			console.log($(selectedElement).text())
		} else {
			alert("Select the line!")
		}
		
	})


} );


</script>
</head>
<body class="dt-example dt-example-bootstrap4">

	<!-- Modal Add -->
	<div class="modal fade" id="addModal" role="dialog">
    	<div class="modal-dialog">
      	<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">ADD DISCOVERY</h4>
				</div>
				<div class="modal-body">
					<div class="input-container">
						<label for="frequency" class="lable">FREQUENCY </label>
						<input type="text" id="frequency">
					</div>
					<div class="input-container">
						<label for="modulation">MODULATION</label>
						<select class="select-option" id="modulation">
						</select>
					</div>
					<div class="input-container">
						<label for="group">GROUP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<select class="select-option" id="group">
						</select>
					</div>
					<div class="input-container">
						<label for="antenna">ANTENNA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<select class="select-option" id="antenna">
						</select>
					</div>
					<div class="input-container">
						<label for="discoverer">DISCOVERER&nbsp;</label>
						<select class="select-option" id="discoverer">
						</select>
					</div>
					<div class="input-container">
						<label for="signal" class="lable">SIGNAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" id="signal">
					</div>
					<div class="input-container">
						<label for="location_name" class="lable">LOCATION&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" id="location_name">
					</div>
					<div class="text-container">
						<label for="note" class="lable">NOTE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<textarea id="note" cols="21" rows="3"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" id="saveButton">Save</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Edit -->
	<div class="modal fade" id="editModal" role="dialog">
    	<div class="modal-dialog">
      	<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">EDIT DISCOVERY</h4>
				</div>
				<div class="modal-body">
					<div class="input-container">
						<label for="discovery_id" class="lable">ID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" id="discovery_id" disabled>
					</div>
					<div class="input-container">
						<label for="frequency1" class="lable">FREQUENCY </label>
						<input type="text" id="frequency1">
					</div>
					<div class="input-container">
						<label for="modulation1">MODULATION</label>
						<select class="select-option" id="modulation1">
						</select>
					</div>
					<div class="input-container">
						<label for="group1">GROUP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<select class="select-option" id="group1">
						</select>
					</div>
					<div class="input-container">
						<label for="antenna1">ANTENNA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<select class="select-option" id="antenna1">
						</select>
					</div>
					<div class="input-container">
						<label for="discoverer1">DISCOVERER&nbsp;</label>
						<select class="select-option" id="discoverer1">
						</select>
					</div>
					<div class="input-container">
						<label for="signal1" class="lable">SIGNAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" id="signal1">
					</div>
					<div class="input-container">
						<label for="location_name1" class="lable">LOCATION&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" id="location_name1">
					</div>
					<div class="text-container">
						<label for="note1" class="lable">NOTE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<textarea id="note1" cols="21" rows="3"></textarea>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" id="updateData">Update</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="container">
		<section>
			<div class="button-container">
				<button type="button" class="control-button" id="addButton">NEW</button>
				<button type="button" class="control-button" id="editButton">EDIT</button>
				<button type="button" class="control-button" id="deleteButton">DELETE</button>
			</div>
		</section>
		<section>
			<div class="demo-html"></div>
				<table id="discovery_table" class="display" style="width:100%">
					<thead>
						<tr>
							<th></th>
							<th>ID</th>
							<th>DATETIME </th>
							<th>FREQUENCY </th>
							<th>MODULATION </th>
							<th>GROUP </th>
							<th>ANTENNA </th>
							<th>SIGNAL </th>
							<th>LOCATION</th>
							<th>DISCOVERER  </th>
						</tr>
					</thead>
				</table>
		</section>
	</div>


<?php include '../../layout/footer.php'; ?>
