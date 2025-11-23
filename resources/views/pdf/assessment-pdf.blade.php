<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Real Property Field Appraisal & Assessment Sheet - Land / Other Improvements</title>
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
        margin-bottom: 30px;
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
    .field_xxl {
      display: inline-block;
      border-bottom: 1px solid black;
      min-width: 100%;
      padding: 0 3px 2px 3px;
      line-height: 1.2;
      vertical-align: bottom;
    }
</style>
</head>
<body>

<h3>REAL PROPERTY FIELD APPRAISAL & ASSESSMENT SHEET - LAND / OTHER IMPROVEMENTS</h3>
<div class="mb-1">
  <div>
    <div class="text-end">
      TRANSACTION CODE: <span class="field_xs"></span>
    </div>
  </div>
</div>
<table>
    <tr>
        <td width="50%">ARP No.: <b><?= $properties->arp_no ?></b></td>
        <td>PIN: <b><?= $properties->pin ?></b></td>
    </tr>
    <tr>
        <td>OCT/TCT/CLOA No.: <span><?= $properties->otc ?></span></td>
        <td>Survey No.: <b></b><?= $properties->survey_no ?></td>
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


<!-- Property Location -->
 <div class="mt-1 display"><b>Property Location</b></div>
<table>
    <tr>
        <td>No./Street: <span><?= $properties->street ?></span></td>
        <td>Brgy./District: <b><?= $properties->brgy ?></b></td>
    </tr>
    <tr>
        <td>Municipality: <b>Inopacan</b></td>
        <td>Province/City: <b>Leyte</b></td>
    </tr>
</table>

<!-- Property Boundaries -->
 <div class="mt-3 display"><b>Property Boundaries</b></div>
<table>
    <tr>
        <td>
            North: <b><?= $properties->north ?></b><br>
            East: <b><?= $properties->east ?></b><br>
            South: <b><?= $properties->south ?></b><br>
            West: <b><?= $properties->west ?></b>
        </td>
        <td class="land-sketch">(Not necessarily drawn to scale)</td>
    </tr>
</table>

<!-- Land Appraisal -->
 <div class="mt-2 display"><b>Land Appraisal</b></div>
<table>
    <tr class="center">
        <td><b>Classification</b></td>
        <td><b>Sub-Classification</b></td>
        <td><b>Area</b></td>
        <td><b>Unit Value</b></td>
        <td><b>Base Market Value</b></td>
    </tr>
    <tr>
        <td><?= $properties->property_classification ?></td>
        <td><?= $properties->sub_classification ?></td>
        <td><?= $properties->area ?> sq.m</td>
        <td class="right">₱ <?= $properties->market_value_data ?></td>
        <td class="right">₱ <?= number_format(($properties->area * $properties->market_value_data), 2) ?></td>
    </tr>
    <tr>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
    </tr>
    <tr>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
    </tr>
    <tr>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
    </tr>
    <tr>
        <td colspan="4" class="right"><b>Total</b></td>
        <td class="right"><b>₱ <?= number_format(($properties->area * $properties->market_value_data), 2) ?></b></td>
    </tr>
</table>
<div>
  Adjustment Factor:
</div>
<!-- Other Improvements -->
 <div class="mt-1 display">
  <b>Other Improvements</b>
 </div>
<table>
    <tr class="center">
        <td><b>Kind</b></td>
        <td><b>Total Number</b></td>
        <td><b>Unit Value</b></td>
        <td><b>Base Market Value</b></td>
    </tr>
    <tr>
        <td class="blank"></td>
        <td class="blank"></td>
        <td class="blank"></td>
        <td class="blank"></td>
    </tr>
    <tr>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
    </tr>
    <tr>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
    </tr>
    <tr>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
    </tr>
    <tr>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
    </tr>
    <tr>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
    </tr>
     <tr>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
    </tr>
    <tr>
        <td colspan="3" class="right"><b>Total</b></td>
        <td></td>
    </tr>
</table>
<div class="page-break"></div>
<div class="display"><b>Property Assessment</b></div>
<table>
    <tr class="center">
        <td><b>Actual Use</b></td>
        <td><b>Market Value</b></td>
        <td><b>Assessment Level</b></td>
        <td><b>Assessment Value</b></td>
    </tr>
    <tr>
        <td><?= $properties->ActualUse ?></td>
        <td>₱ <?= number_format(($properties->area * $properties->market_value_data), 2) ?></td>
        <td><?= $properties->assessment_rate ?>%</td>
        <td>₱ <?= number_format(($properties->area * $properties->market_value_data * ($properties->assessment_rate / 100)), 2) ?></td>
    </tr>
    <tr>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
    </tr>
    <tr>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
    </tr>
    <tr>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
      <td class="blank"></td>
    </tr>
    <tr>
        <td colspan="3" class="right"><b>Total</b></td>
        <td>₱ <?= number_format(($properties->area * $properties->market_value_data * ($properties->assessment_rate / 100)), 2) ?></td>
    </tr>
   <tr>
      <td class="border-rg">
        <label for="Taxable" class="checkbox-label">Taxable</label>
        <input type="checkbox" id="Taxable" <?= $properties->istaxable == 0 ? " " : 'checked'?>  class="checkbox-label">
      </td>
      <td class="border-lf border-rg">
        <label for="Exempt" class="checkbox-label">Exempt</label>
        <input type="checkbox" id="Exempt"  <?= $properties->isexempted == 0 ? " " : 'checked'?>  class="checkbox-label">
      </td>
      <td class="border-rg border-lf">
        Effectivity of Assessment/Reassessment
      </td>
      <td class="border-lf">
        <span class="field_xxs text-center"></span>
        <span class="field_xs text-center"></span>
      </td>
    </tr>
</table>
<div class="mt-1">
  <div class="display"><b>Adjustment Factor</b></div>
  <div><b>1. Type of Road</b></div>
  <div>a. No road outlet <span style="margin-left: 101px;" class="field_xxs text-center"><?= $properties->outlet_road == 0 ? " " : '0'?></span></div>
  <div>b. Along dirt road <span style="margin-left: 99px;" class="field_xxs text-center"><?= $properties->dirt_road == 0 ? " " : '0'?></span></div>
  <div>c. Along weather road <span style="margin-left: 73px;" class="field_xxs text-center"><?= $properties->weather_road == 0 ? " " : '0'?></span></div>
  <div>d. Along national/ prov'|.road <span style="margin-left: 37px;" class="field_xxs text-center"><?= $properties->provincial_road == 0 ? " " : '0'?></span> <span style="margin-left: 50px;" class="field_xs"></span></div>
  <div><b>2. Location</b></div>
  <div>a. Distance to all weather road.</div>
  <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Km. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%Adjustment</div>
  <div><span style="margin-left: 20px;" class="field_xs text-center">0</span><span style="margin-left: 20px;" class="field_xs text-center">%</span><span style="margin-left: 40px;" class="field_xs text-center"></span></div>
  <div class="mt-1">b. Distance to trading center.</div>
  <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Km. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%Adjustment</div>
  <div><span style="margin-left: 20px;" class="field_xs text-center">0</span><span style="margin-left: 20px;" class="field_xs text-center">%</span><span style="margin-left: 40px;" class="field_xs text-center"></span></div>
  <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Km. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%Adjustment</div>
  <div class="mt-1"><span style="margin-left: 160px; font-weight: bold" class="text-center">Total</span><span style="margin-left: 44px;" class="field_xs text-center"></span></div>
</div>
<div class="mt-2 display">
  <b>Record of Susperseded Assessment</b>
</div>
<table class="mt-1">
    <tr>
        <td colspan="2">PIN: <b><?= $properties->pin?></b></td>
    </tr>
    <tr>
      <td>ARP No. <b><?= $properties->arp_no?></b></td>
      <td>TD No.</td>
    </tr>
    <tr>
      <td colspan="2">Total Assessed Value: <b>₱ <?= number_format(($properties->area * $properties->market_value_data * ($properties->assessment_rate / 100)), 2) ?></b></td>
    </tr>
    <tr>
      <td colspan="2">Previous Owner: <b><?= $properties->previous_owner ?></b></td>
    </tr>
    <tr>
      <td colspan="2">Effectivity of Assessment:</td>
    </tr>
     <tr>
      <td colspan="2">AR Page No:</td>
    </tr>
   <tr>
      <td class="border-rg">
        <label for="Recording" class="checkbox-label">Recording Person</label>
      </td>
      <td class="border-lf">
        <label for="Date" class="checkbox-label">Date</label>
      </td>
    </tr>
</table>
<div style="margin-top: 20px;">
  <div class="display">
    <span><b>Appraised/Assessed/Prepared By:</b></span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <span><b>Recommending Approval:</b></span>
  </div>
  <div class="mt-1">
    <div>
      <span style="margin-left: 20px;" class="field_sm text-center"><?= $Assessment_Clerk_1->fullname ?></span>
      <span style="margin-left: 20px;" class="field_xs text-center"><?= date("d/m/Y", strtotime(now())); ?></span>
      <span style="margin-left: 60px;" class="field_sm text-center"><?=  $properties->fullname?></span>
      <span style="margin-left: 20px;" class="field_xs text-center"><?= date("d/m/Y", strtotime(now())); ?></span>
    </div>
  </div>
  <div>
    <div>
      <span style="margin-left: 60px;">
        Assesment Clerk
      </span>
      <span style="margin-left: 62px;">
        Date
      </span>
      <span style="margin-left: 110px;">
        Municilap Assesor
      </span>
      <span style="margin-left: 65px;">
        Date
      </span>
    </div>
  </div>
  <div style="margin-top: 15px;">
    <span class="display"><b>Approved by:</b></span>
  </div>
  <div class="mt-2">
    <div>
      <span style="margin-left: 70px;" class="field_sm text-center"><?= $OIC_Municilap_Assessor->fullname ?></span>
      <span style="margin-left: 180px;" class="field_xs text-center"><?= date("d/m/Y", strtotime(now())); ?></span>
    </div>
  </div>
  <div>
    <div>
      <span style="margin-left: 80px;">
        OiC-Provincial Assessor
      </span>
      <span style="margin-left: 220px;">
        Date
      </span>
    </div>
  </div>
  <div class="mt-1">
    <span class="field_xxl"></span>
    <span class="field_xxl"></span>
    <span class="field_xxl"></span>
    <span class="field_xxl"></span>
    <span class="field_xxl"></span>
    <span class="field_xxl"></span>
  </div>
  <div class="mt-1">
     <div>Data of Entry in the Record of the Assessment <span style="margin-left: 99px;" class="field_xxs text-center"></span> By: <span style="margin-left: 5px;" class="field_sm text-center"></span></div>
  </div>
</div>
</body>
</html>
