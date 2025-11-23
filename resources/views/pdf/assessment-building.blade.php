<?php
$storey = array_map('intval', explode(',', $properties->storey_size));
$totalStorey = array_sum($storey);

$market_value = $totalStorey * $properties->construction_cost;
$assessment_value = $market_value * ($properties->percentage / 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Real Property Field Appraisal & Assessment Sheet - Building / Other Structure</title>
  <style>
    body {
        font-family: "DejaVu Sans", Arial, sans-serif;
        font-size: 10px;
        color: #000;
        margin: 30px;
    }

    h3 {
        text-align: center;
        text-transform: uppercase;
        margin-bottom: 10px;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 6px;
    }

    td {
        border: 1px solid #000;
        padding: 4px 6px;
        vertical-align: top;
    }

    .no-border td {
        border: none;
    }

    .section-title {
        background-color: #f2f2f2;
        font-weight: bold;
        text-transform: uppercase;
        padding: 3px 5px;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .land-sketch {
        height: 60px;
        text-align: center;
        font-style: italic;
    }

    .blank {
        height: 14px;
    }

    @media print {
        body {
            margin: 15mm;
        }
    }
    .mt-1{
      margin-top: 8px;
    }
    .mt-2{
      margin-top: 20px;
    }
    .mt-3{
      margin-top: 32px;
    }
    .mb-1{
      margin-bottom: 15px;
    }
    .display{
      text-transform: uppercase;
      font-size: 12px;
    }
    .field_xs {
      display: inline-block;
      border-bottom: 1px solid black;
      min-width: 70px;
      padding: 0 3px 2px 3px;
      line-height: 1.2;
      vertical-align: bottom;
    }
    .field_md {
      display: inline-block;
      border-bottom: 1px solid black;
      min-width: 175px;
      padding: 0 3px 2px 3px;
      line-height: 1.2;
      vertical-align: bottom;
    }
    .text-end {
      text-align: right;
      width: 100%;
    }
    .page-break {
        page-break-before: always;
    }
    .field_xxs {
      display: inline-block;
      border-bottom: 1px solid black;
      min-width: 50px;
      padding: 0 3px 2px 3px;
      line-height: 1.2;
      vertical-align: bottom;
    }
    .text-center {
      text-align: center;
    }
    .checkbox-label {
      vertical-align: middle;
      margin: 0;
    }
    .border-lf{
      border-left: none;

    }
    .border-rg{
      border-right: none;
    }
    .field_sm {
      display: inline-block;
      border-bottom: 1px solid black;
      min-width: 150px;
      padding: 0 3px 2px 3px;
      line-height: 1.2;
      vertical-align: bottom;
    }
    .field_xxl{
      display: inline-block;
      border-bottom: 1px solid black;
      min-width: 550px;
      padding: 0 3px 2px 3px;
      line-height: 1.2;
      vertical-align: bottom;
    }
    .text-sm{
      font-size: 11px;
    }
</style>
</head>
<body>
<h3>REAL PROPERTY FIELD APPRAISAL & ASSESSMENT SHEET - BUILDING / OTHER STRUCTURES</h3>
<div class="mb-1">
  <div>
    <div class="text-end">
      TRANSACTION CODE: <span class="field_xs"></span>
    </div>
  </div>
</div>
<table>
    <tr>
        <td>ARP No.: <b><?= $properties->arp_no ?></b></td>
        <td>PIN: <b><?= $properties->pin ?></b></td>
    </tr>
    <tr>
        <td>Owner: <b><?= $properties->owner ?></b></td>
        <td>Tel. No.: <b></b></td>
    </tr>
    <tr>
        <td>Address: <b><?= $properties->address ?></b></td>
        <td>TIN: <b><?= $properties->tin?></b></td>
    </tr>
    <tr>
        <td>Administrator/Beneficial User: <span></span></td>
        <td>Tel. No.: <span></span></td>
    </tr>
    <tr>
        <td>Address: <b></b></td>
        <td>TIN: <b></b></td>
    </tr>
</table>
<table>
  <tr>
    <td style="border:none">
      <span class="display"><b>BUILDING LOCATION</b></span>
    </td>
    <td style="border:none">
      <span class="display"><b>LAND REFERENCE</b></span>
    </td>
  </tr>
    <tr>
        <td>No./Street: <b><?= $properties->street ?></b></td>
        <td>Owner: <b><?= $properties->owner ?></b></td>
    </tr>
    <tr>
        <td rowspan="2">Brgy./District <b><?= $properties->brgy ?></b></td>
        <td>OCT/TCT/CLOA No.: <b><?= $properties->otc ?></b>&nbsp;&nbsp;&nbsp;<span>Survery No. <b><?= $properties->survey_no ?></b></span></td>
    </tr>
    <tr>
        <td>Lot Number: <b><?= $properties->lot_number ?></b>&nbsp;&nbsp;&nbsp;<span>Blk No.</span></td>
    </tr>
    <tr>
        <td>Municipality: <span><b>Inopacan</b></span></td>
        <td>APR. No.: <span><b><?= $properties->arp_no?></b></span></td>
    </tr>
    <tr>
        <td>Province/City: <b>Leyte</b></td>
        <td>Area: <b><?= $properties->area ?></b></td>
    </tr>
</table>
<div class="display">
  <b>General Description</b>
</div>
<table>
    <tr>
        <td>Kind of Bldg: <b><?= $properties->classification ?></b></td>
        <td>Bldg Age: <b></b></td>
    </tr>
    <tr>
        <td>Structural Type: <b><?= $properties->structural_type?></b></td>
        <td>Number of Storeys: <b><?= $properties->storey ?></b></td>
    </tr>
    <tr>
        <td>Bldg. Permit No. <b></b></td>
        <td>Area of 1st flr: <b><?= $storey[0] ?? 0 ?> sq. m</b></td>
    </tr>
    <tr>
        <td>Condominium Certificate Title (CCT): <span></span></td>
        <td>Area of 2nd flr: <b><?= $storey[1] ?? 0 ?> sq. m</b></td>
    </tr>
    <tr>
        <td>Certification of Complete Issued On: <b></b></td>
        <td>Area of 3rd flr:<b><?= $storey[2] ?? 0 ?> sq. m</b></td>
    </tr>
    <tr>
        <td>Certificate Occupancy Issued On: <b></b></td>
        <td>Area of 4th flr: <b><?= $storey[3] ?? 0?> sq. m</b></td>
    </tr>
    <tr>
        <td>Date Constructed/Complete: <b><?= $properties->complete ?></b></td>
        <td></td>
    </tr>
    <tr>
        <td>Date Occupied: <b><?= $properties->complete ?></b></td>
        <td>Total Floor Area: <b><?= $totalStorey ?> sq. m</b></td>
    </tr>
</table>
<div class="mt-1 display">
  <b>Floor Plan</b>
</div>
<table>
  <tr>
    <td class="text-center">
      Attach the building plan or sketch of floor plan. A photograph may also be attached if necessary
    </td>
  </tr>
</table>
<div class="mt-1">
  <b class="display">Structural Materials</b><span> <b>(Checklists)</b></span>
</div>
<table border="1" cellspacing="0" cellpadding="5" style="width:100%; text-align:center; border-collapse:collapse;">
  <tr>
    <th colspan="2">ROOF</th>
    <th >FLOORING</th>
    <th>1st Flr.</th>
    <th>2nd Flr.</th>
    <th>3rd Flr.</th>
    <th>4th Flr.</th>
    <th>WALLS & PARTITIONS</th>
    <th>1st Flr.</th>
    <th>2nd Flr.</th>
    <th>3rd Flr.</th>
    <th>4th Flr.</th>
  </tr>

  <tr>
    <td>Reinforced Concrete</td>
    <td><?= $properties->roof == "Reinforced Concrete"? 'x' : ''?></td>
    <td>Reinforced Concrete</td>
    <td><?= $properties->flooring == "Reinforced Concrete"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Reinforced Concrete"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Reinforced Concrete"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Reinforced Concrete"? 'x' : ''?></td>
    <td>Reinforced Concrete</td>
    <td><?= $properties->walls == "Reinforced Concrete"? 'x' : ''?></td>
    <td><?= $properties->walls == "Reinforced Concrete"? 'x' : ''?></td>
    <td><?= $properties->walls == "Reinforced Concrete"? 'x' : ''?></td>
    <td><?= $properties->walls == "Reinforced Concrete"? 'x' : ''?></td>
  </tr>

  <tr>
    <td>Tiles</td>
    <td><?= $properties->roof == "Tiles"? 'x' : ''?></td>
    <td>Plain Cement</td>
    <td><?= $properties->flooring == "Plain Cement"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Plain Cement"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Plain Cement"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Plain Cement"? 'x' : ''?></td>
    <td>Plain Cement</td>
    <td><?= $properties->walls == "Plain Cement"? 'x' : ''?></td>
    <td><?= $properties->walls == "Plain Cement"? 'x' : ''?></td>
    <td><?= $properties->walls == "Plain Cement"? 'x' : ''?></td>
    <td><?= $properties->walls == "Plain Cement"? 'x' : ''?></td>
  </tr>

  <tr>
    <td>G.I. Sheet</td>
    <td><?= $properties->roof == "G.I. Sheet"? 'x' : ''?></td>
    <td>Marble</td>
    <td><?= $properties->flooring == "Marble"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Marble"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Marble"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Marble"? 'x' : ''?></td>
    <td>Wood</td>
    <td><?= $properties->walls == "Wood"? 'x' : ''?></td>
    <td><?= $properties->walls == "Wood"? 'x' : ''?></td>
    <td><?= $properties->walls == "Wood"? 'x' : ''?></td>
    <td><?= $properties->walls == "Wood"? 'x' : ''?></td>
  </tr>

  <tr>
    <td>Aluminum</td>
    <td><?= $properties->roof == "Aluminum"? 'x' : ''?></td>
    <td>Wood</td>
    <td><?= $properties->flooring == "Wood"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Wood"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Wood"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Wood"? 'x' : ''?></td>
    <td>CHB</td>
    <td><?= $properties->walls == "CHB"? 'x' : ''?></td>
    <td><?= $properties->walls == "CHB"? 'x' : ''?></td>
    <td><?= $properties->walls == "CHB"? 'x' : ''?></td>
    <td><?= $properties->walls == "CHB"? 'x' : ''?></td>
  </tr>

  <tr>
    <td >Asbestos</td>
    <td><?= $properties->roof == "Asbestos"? 'x' : ''?></td>
    <td>Tiles</td>
    <td><?= $properties->flooring == "Tiles"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Tiles"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Tiles"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Tiles"? 'x' : ''?></td>
    <td>G.I. Sheet</td>
    <td><?= $properties->walls == "G.I. Sheet"? 'x' : ''?></td>
    <td><?= $properties->walls == "G.I. Sheet"? 'x' : ''?></td>
    <td><?= $properties->walls == "G.I. Sheet"? 'x' : ''?></td>
    <td><?= $properties->walls == "G.I. Sheet"? 'x' : ''?></td>
  </tr>

  <tr>
    <td >Long Span</td>
    <td><?= $properties->roof == "Long Span"? 'x' : ''?></td>
    <td>Others (Specify)</td>
    <td><?= $properties->flooring == "Others"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Others"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Others"? 'x' : ''?></td>
    <td><?= $properties->flooring == "Others"? 'x' : ''?></td>
    <td>Build-a-wall</td>
    <td><?= $properties->walls == "Build-a-wall"? 'x' : ''?></td>
    <td><?= $properties->walls == "Build-a-wall"? 'x' : ''?></td>
    <td><?= $properties->walls == "Build-a-wall"? 'x' : ''?></td>
    <td><?= $properties->walls == "Build-a-wall"? 'x' : ''?></td>
  </tr>

  <tr>
    <td >Concrete Deck</td>
    <td><?= $properties->roof == "Concrete Deck"? 'x' : ''?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Sawali</td>
    <td><?= $properties->walls == "Sawali"? 'x' : ''?></td>
    <td><?= $properties->walls == "Sawali"? 'x' : ''?></td>
    <td><?= $properties->walls == "Sawali"? 'x' : ''?></td>
    <td><?= $properties->walls == "Sawali"? 'x' : ''?></td>
  </tr>

  <tr>
    <td >Nipa/Anahaw/Cogon</td>
    <td><?= $properties->roof == "Nipa/Anahaw/Cogon"? 'x' : ''?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Bamboo</td>
    <td><?= $properties->walls == "Bamboo"? 'x' : ''?></td>
    <td><?= $properties->walls == "Bamboo"? 'x' : ''?></td>
    <td><?= $properties->walls == "Bamboo"? 'x' : ''?></td>
    <td><?= $properties->walls == "Bamboo"? 'x' : ''?></td>
  </tr>

  <tr>
    <td >Color Roof</td>
    <td><?= $properties->roof == "Color Roof"? 'x' : ''?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Others (Specify)</td>
    <td><?= $properties->walls == "Others"? 'x' : ''?></td>
    <td><?= $properties->walls == "Others"? 'x' : ''?></td>
    <td><?= $properties->walls == "Others"? 'x' : ''?></td>
    <td><?= $properties->walls == "Others"? 'x' : ''?></td>
  </tr>
  <tr>
    <td >Others (Specify)</td>
    <td><?= $properties->roof == "Others"? 'x' : ''?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
<div class="page-break"></div>
<div class="mt-1">
  <b class="display">Additional items:</b><span> (Use additional sheet if necessary)</span>
</div>
<table>
  <tr>
    <td class="blank"></td>
    <td class="blank"></td>
    <td class="blank"></td>
    <td class="blank"></td>
    <td class="blank"></td>
  </tr>
  <tr>
    <td colspan="5" class="blank"></td>
  </tr>
  <tr>
    <td colspan="5" class="blank"></td>
  </tr>
</table>
<div class="mt-2">
  <b class="display">Property Appraisal</b>
</div>
<table>
  <tr>
    <td>Unit Construction Cost: <span class="field_sm">₱ <?= number_format($properties->construction_cost , 2) ?></span> /sq.m
      <div>
        Building Core: (Use additional sheet if necessary)
      </div>
      <div style="margin-top: 15px;">
        Total Cost per Detailed Estimates=<b>₱ <?= number_format($market_value, 2) ?></b>
      </div>
      <div style="margin-top: 25px;">
        Sub-Total:
      </div>
    </td>
    <td>Cost of Additional Items:
      <div style="margin-top: 50px;">
        Sub-Total:
      </div>
      <div>
        Total Construction Cost:
      </div>
    </td>
  </tr>
  <tr>
    <td>Deprication Rate:</td>
    <td>Total % Deprication:</td>
  </tr>
  <tr>
    <td>Deprication Cost</td>
    <td>Market Value: <b>₱ <?= number_format($market_value, 2) ?></b></td>
  </tr>
</table>
<div class="mt-2">
  <b class="display">Property Assessment</b>
</div>
<table>
  <tr>
    <td>Actual Use</td>
    <td>Market Value</td>
    <td>Assessment Level</td>
    <td>Assesment Value</td>
  </tr>
  <tr>
    <td><?= $properties->classification ?></td>
    <td>₱ <?= number_format($market_value, 2) ?></td>
    <td><?= $properties->percentage ?>%</td>
    <td>₱ <?= number_format($assessment_value, 2) ?></td>
  </tr>
  <tr>
    <td></td>
    <td><b>₱ <?= number_format($market_value, 2) ?></b></td>
    <td></td>
    <td><b>₱ <?= number_format($assessment_value, 2) ?></b></td>
  </tr>
  <tr>
    <td style="border-right: none;">
      <label for="Taxable" class="checkbox-label">Taxable</label>
      <input type="checkbox" id="Taxable"  class="checkbox-label">
    </td>
    <td style="border-left: none; border-right: none;">
      <label for="Exempt" class="checkbox-label">Exempt</label>
      <input type="checkbox" id="Exempt"  class="checkbox-label">
    </td>
    <td style="border-right: none; border-left: none;">
      Effectivity of Assessment/Reassessment
    </td>
    <td style="border-left: none;">
      <span class="field_xxs text-center">2</span>
      <span class="field_xs text-center">2025</span>
    </td>
  </tr>
</table>
<table class="mt-1">
  <tr>
    <td style="border:none"><b>Prepared by:</b></td>
    <td style="border:none"><b>Noted by:</b></td>
    <td style="border:none"><b>Appraised by:</b></td>
  </tr>
  <tr>
    <td style="border:none" class="mt-2"><span class="display field_md text-cente text-sm"><b><?= $Assessment_Clerk_1->fullname ?></b></span></td>
    <td style="border:none" class="mt-2"><span class="display field_md text-center text-sm"><b><?= $OIC_Municilap_Assessor->fullname ?></b></span></td>
    <td style="border:none" class="mt-2"><span class="display field_md text-center text-sm"><b><?= $Technical_Supervisor->fullname ?></b></span></td>
  </tr>
  <tr>
    <td style="border:none;" class="text-center">Assessment Clerk 1</td>
    <td style="border:none;" class="text-center">OIC Municilap Assessor</td>
    <td style="border:none;" class="text-center">Technical Supervisor</td>
  </tr>
</table>
<div class="mt-2">
  <span class="display"><b>Approved by:</b></span>
</div>
<div class="mt-2">
<span style="margin-left: 130px" class="field_md text-center text-sm"><b><?= $properties->fullname ?></b></span><span style="margin-left: 50px" class="field_xs text-center"><?=  date('M. d, Y', strtotime(now())) ?></span>
</div>
<div><span style="margin-left: 160px;">Provincial Assessor</span><span style="margin-left: 120px;">Date</span></div>
<div class="mt-2">
  <span class="display"><b>Memoranda:</b></span><span style="text-decoration: underline; ">
    This is to certify that the real property herein described has been occularly inspected and found out to be already occupied. Date inspected: <?= date('M. d, Y', strtotime($properties->created_at))?>. Attached herewith is the bill of quantities.
  </span>
</div>
<div class="mt-2">
  <span><b>Data of Entry in the Record of Assessement: </b></span>
  <span class="field_xs" style="margin-left: 60px;"></span>
  <span style="margin-left: 20px;">By:</span><span class="field_md"></span>
</div>
<div>
  <span style="margin-left: 500px">Name</span>
</div>
<div class="mt-2">
  <span class="display"><b>Record of Susperseded Assessment</b></span>
</div>
<table class="mt-1">
  <tr>
    <td colspan="2">PIN: <b>NEW</b></td>
  </tr>
  <tr>
    <td>ARP No. <b>NEW</b></td>
    <td>TD No. <b>NEW</b></td>
  </tr>
  <tr>
    <td colspan="2">Total Assessed Value: <b>NEW</b></td>
  </tr>
  <tr>
    <td colspan="2">Previous Owner:  <b>NEW</b></td>
  </tr>
  <tr>
    <td colspan="2">Effectivity of Assessment: <b>NEW</b></td>
  </tr>
  <tr>
    <td colspan="2">
      <span>Recording Person: <span class="field_md"></span></span><span style="margin-left: 80px;">Date: <span class="field_xs"></span></span>
    </td>
  </tr>
</table>
</body>
</html>
