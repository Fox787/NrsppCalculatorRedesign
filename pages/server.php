<?php
	session_start();
	$log="" ;
	$errors = array();
	
	$db = mysqli_connect("localhost","root","","calculator") ;
	if (!$db) {
        echo"dead";
		die("Connection failed: " . mysqli_connect_error());
	}
    
    echo "here";
	// this part is to handle results.php
	if (isset($_POST['Calculator'])){
		// getting variables from Calculator.php
		$id_sec = mysqli_real_escape_string($db,$_POST['id_sec']); // 1.1
        $prodName = mysqli_real_escape_string($db,$_POST['prodName']); //1.2

		$unitSales = mysqli_real_escape_string($db,$_POST['unitSales']); //1.3
        $productReturn = mysqli_real_escape_string($db,$_POST['productReturn']);//2.8
        $grossTurn = mysqli_real_escape_string($db,$_POST['grossTurn']); //2.9
        $averageProduct = mysqli_real_escape_string($db,$_POST['averageProduct']);//2.11
        
        $insurersCost = mysqli_real_escape_string($db,$_POST['insurersCost']);//2.1
        $numIncidents = mysqli_real_escape_string($db,$_POST['numIncidents']);//2.2
		$numVehicles = mysqli_real_escape_string($db,$_POST['numVehicles']); //2.3
        $distanceFleet = mysqli_real_escape_string($db,$_POST['distanceFleet']);//2.4
        $insunceFleet = mysqli_real_escape_string($db,$_POST['insunceFleet']);//2.5
		$preVehicles = mysqli_real_escape_string($db,$_POST['preVehicles']);//2.6
        $excessClaim = mysqli_real_escape_string($db,$_POST['excessClaim']);//2.7
        
        $anualGreyFleet = mysqli_real_escape_string($db,$_POST['anualGreyFleet']);//2.12
		$kmCombinedFleet = mysqli_real_escape_string($db,$_POST['kmCombinedFleet']);//2.13
        echo "calc";
        //CALCULATIONS
        $productProfit = $productReturn * $grossTurn;//2.10

        $overallClaimRate = $numIncidents * (100000/$distanceFleet); //3.1
        $distanceClaimCombined = $distanceFleet / $numIncidents;// 3.2
        $overallPerVechile = ($numIncidents / $numVehicles) * 100; //3.3

        //DirectCosts
        $directcost = $numVehicles * $productProfit; //3.4
        $percentGrossTurn = ($directcost/$grossTurn) * 100; //3.5
        $percentNet = ($directcost/$productProfit) * 100;//3.6

        //Indirect Costs
        $indirectCost = $insurersCost * 3; //3.7
        $percentIndirect = ($indirectCost / $grossTurn) * 100;//3.8
        $percentNet = ($indirectCost / $productProfit) * 100;//3.9

        //Estimated Total - Managed Fleet
        $estimatedTotal = $directcost + $indirectCost; //3.10
        $estimatedGross = ($estimatedTotal / $grossTurn) *100; //3.11
        $estimatedNet = ($estimatedTotal / $productProfit) *100;//3.12

        $unitRevenue = $productReturn * $averageProduct; //4.1

        //HIDDEN CALCS
        $HiddenIndirectCost = $insurersCost * 3; //5.1
        $HiddenTotalCost = $directcost + $HiddenIndirectCost; //5.2
        $HiddenAverage = $HiddenTotalCost / $numIncidents; //5.3
        $EOCN = ($anualGreyFleet * $kmCombinedFleet) / $distanceClaimCombined;//5.4
        $EOCC =  $EOCN * $HiddenAverage;//5.5
        $REOCC = $EOCC /("0."+$productReturn) ;//5.6

       echo "end";

        //Estimated Total - All
        $AllTotal = $estimatedTotal + $REOCC;//3.13
        $AllGross = ($AllTotal / $grossTurn) * 100;//3.14
        $AllNet = ($AllTotal / $productProfit) * 100;//3.15

		//Processing the Result  into table
			$sql = "INSERT INTO results (SectorName, DirectIncident, IndirectIncident, IncidentManaged, IncidentCostAll)
				VALUES ('$id_sec', '$directcost', '$indirectCost','$estimatedTotal', $AllTotal)" ;
            mysqli_query($db,$sql) ;
            echo "sql";
            $_SESSION['log'] = $AllTotal;
            $_SESSION['success'] = "You are logged in";
			header('location: test.php');
	}		
?>