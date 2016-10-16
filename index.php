<!DOCTYPE html>
<html ng-app = "myApp" ng-cloak>
<head>
	<!-- Title -->
	<title></title>
	<!-- Meta tags -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">	
	<meta charset="utf-8">
	<!-- CSS stylesheets -->
	<link rel="stylesheet" href="css/materialize.css">	
	<link rel="stylesheet" href="fonts/material-icons/material-icons.css">	
	<link rel="stylesheet" href="css/index.css">	
	<!-- Javascript files -->
	<script src = "lib/jquery.min.js"></script> 
	<script src = "lib/angular.min.js"></script>	
 	<script src = "lib/angular-sanitize.min.js"></script>
 	<script src = "lib/angular-filters.min.js"></script>
 	<script src = "lib/materialize/bin/materialize.min.js"></script>
</head>
<body ng-controller="main-controller">

	<div class="column-wrapper">
		<div class="column" id="column-1">
			<h4>Databases</h4>
			<div class="row database-item" ng-repeat="d in databases" ng-click = "getTables(d, $event)">{{d}}</div>
		</div>
		<div class="column" id="column-2">		
			<h4>Tables</h4>
			<div class="row table-item" ng-repeat="t in tables"  ng-click = "getTableDesc(t, $event)">{{t}}</div>
		</div>
		<div class="viewer">  
			<nav>
    			<div class="nav-wrapper">
		        	<a href="#!" class="breadcrumb">{{currentDB}}</a>
		        	<a href="#!" class="breadcrumb">{{currentTable}}</a> 
      			</div>
      		</nav>
			<table class = "responsive-table bordered highlight">
				<thead>
					<th>Field</th>
					<th>Type</th>
					<th>Null</th>
					<th>Key</th>
					<th>Default</th>
					<th>Extra</th>
				</thead>
				<tbody>
					<tr ng-repeat = "td in tableDesc">
						<td>{{td.Field}}</td>
						<td>{{td.Type}}</td>
						<td>{{td.Null}}</td>
						<td>{{td.Key}}</td>
						<td>{{td.Default}}</td>
						<td>{{td.Extra}}</td>
						<td class ="add-btn" ng-click = "addField(td)"><i class="material-icons">add</i></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="fixed-action-btn modal-trigger" href="#modal1">			    		
    		<a class="btn-floating btn-large red z-depth-3">
      			<i class="large material-icons">grid_on</i>
    		</a>
    		<span class="badge">{{fields.length}}</span>
    	</div>

    	<div id="modal1" class="modal">
		    <div class="modal-content">
		      	<h4>Atributos</h4>
		      	<p class = "item-added-wrapper">		      		
		      		<div class = "item-added" ng-repeat = "f in fields">
		      			<i class="material-icons remove-btn" ng-click = "removeField($index)">remove_circle_outline</i>
		      			<span class = "item-bd">{{f.Database}}</span>
		      			<i class="material-icons">navigate_next</i>
		      			<span class = "item-table">{{f.Table}}</span>
		      			<i class="material-icons">navigate_next</i>
		      			<span class = "item-field">{{f.Field}}</span> 
		      			<span class = "item-field type-field">{{f.Type}}</span> 
		      		</div>
		      	</p>
		    </div>
		    <div class="modal-footer">
		    	<a href="#!" class=" modal-action modal-close waves-effect waves-yellow btn-flat">Cerrar</a>
		    </div>
		</div>

	</div>

	<script type="text/javascript">	
		var app = angular.module("myApp", ['ngSanitize']); 

		app.controller("main-controller", function($scope, $http)
		{
			$scope.currentDB 		= null;
			$scope.currentTable 	= null;
			$scope.databases 	= [];
			$scope.tables 		= [];
			$scope.tableDesc 	= [];
			$scope.fields		= []; 

  			// Get databases data
  			$http.get("php/do.php", { params: { "do":"getDatabases" }
			}).then(function(response){ 
				$scope.databases = response.data;
			}); 

			$scope.getTables = function(database, $event)
			{	
				$(".database-item").removeClass("selected");
				$($event.currentTarget).addClass("selected"); 

				$scope.currentDB = database;
				$scope.currentTable = null;
				$scope.tableDesc 	= [];
				$http.get("php/do.php", { params: { "do":"getTables", "database":database }
				}).then(function(response){  
					$scope.tables = response.data;
				});
			};

			$scope.getTableDesc = function(table, $event)
			{
				$(".table-item").removeClass("selected");
				$($event.currentTarget).addClass("selected"); 

				$scope.currentTable = table;
				$http.get("php/do.php", { params: { "do":"getTableDesc", "database":$scope.currentDB, "table":table }
				}).then(function(response){   
					$scope.tableDesc = response.data;
				});
			};

			$scope.addField = function(field){
				$scope.fields.push(angular.copy(field));
			};

			$scope.removeField = function(index){
				$scope.fields.splice(index, 1);
			}
		});
		$(function(){
			$('.modal-trigger').leanModal(); 
		});
	</script>
</body>
</html>