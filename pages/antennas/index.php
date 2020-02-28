<?php 
    include '../../layout/header.php'; 
?>
<nav class="navbar navbar-expand-sm bg-light">
  <ul class="tabs">
    <li class="nav-item">
      <a class="nav-link" href="/frequency/pages/discovery/">DISCOVERY</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/frequency/pages/modulations/">MODULATIONS</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/frequency/pages/groups/">GROUPS</a>
    </li>
    <li class="nav-item active">
      <a class="nav-link" href="/frequency/pages/antennas/">ANTENNAS</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/frequency/pages/discoverers/">DISCOVERERS</a>
    </li>
  </ul>
</nav>

<br>


<script type="text/javascript" language="javascript" class="init">


var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
	editor = new $.fn.dataTable.Editor( {
		ajax: "./staff.php",
		table: "#antennas_table",
		fields: [ {
				label: "Antenna Name:",
				name: "antenna"
			}
    ],
    formOptions: {
			inline: {
				onBlur: 'submit'
			}
		}
	} );

	var editIcon = function ( data, type, row ) {
		if ( type === 'display' ) {
			return data + ' <i class="fa fa-pencil"/>';
		}
		return data;
	};

	$('#antennas_table tbody').on( 'click', 'td i', function (e) {
		e.stopImmediatePropagation(); // stop the row selection when clicking on an icon

    editor.inline( $(this).parent() );
  } );


	$('#antennas_table').DataTable( {
		dom: "Bfrtip",
		ajax: "./staff.php",
		columns: [
			{ data: "id", },
			{ data: "antenna",  render: editIcon },
		],
		select: true,
		buttons: [
			{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
      { extend: "remove", editor: editor },
      {
				extend: 'collection',
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
	} );
} );



	</script>
</head>
<body class="dt-example dt-example-bootstrap4">
	<div class="container">
		<section>
			<div class="demo-html"></div>
			<table id="antennas_table" class="ditable table-striped table-borderedsplay">
				<thead>
					<tr>
						<th>ID</th>
						<th>Antenna Name</th>
					</tr>
				</thead>
				<tbody/>
			</table>
		</section>
	</div>


<?php include '../../layout/footer.php'; ?>
