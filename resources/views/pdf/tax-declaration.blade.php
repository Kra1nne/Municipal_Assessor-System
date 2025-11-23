<?php
$totalValue = $properties->area * $properties->market_value_data * ($properties->assessment_rate / 100);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tax Declaration</title>
<style>
  body {
    font-family: "DejaVu Sans", Arial, sans-serif;
    font-size: 10px;
    margin: 25px;
  }

  h3 {
    text-align: center;
    margin-bottom: 20px;
    text-transform: uppercase;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
  }

  td {
    padding: 3px;
    vertical-align: top;
    border: none;
  }

  .label {
    font-weight: bold;
  }
  .text-center {
    text-align: center;
  }
  .mt-2{
    margin-top: 10px;
  }
  .field {
    display: inline-block;
    border-bottom: 1px solid black;
    min-width: 120px;
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
  .field_sm {
    display: inline-block;
    border-bottom: 1px solid black;
    min-width: 100px;
    padding: 0 3px 2px 3px;
    line-height: 1.2;
    vertical-align: bottom;
  }
  .field_xs {
    display: inline-block;
    border-bottom: 1px solid black;
    min-width: 70px;
    padding: 0 3px 2px 3px;
    line-height: 1.2;
    vertical-align: bottom;
  }
  .field_xxs {
    display: inline-block;
    border-bottom: 1px solid black;
    min-width: 50px;
    padding: 0 3px 2px 3px;
    line-height: 1.2;
    vertical-align: bottom;
  }
  .field_lg {
    display: inline-block;
    border-bottom: 1px solid black;
    min-width: 250px;
    padding: 0 3px 2px 3px;
    line-height: 1.2;
    vertical-align: bottom;
  }
  .field_xxl {
    display: inline-block;
    border-bottom: 1px solid black;
    min-width: 520px;
    padding: 0 3px 2px 3px;
    line-height: 1.2;
    vertical-align: bottom;
  }
  .field_0lg {
    display: inline-block;
    border-bottom: 1px solid black;
    min-width: 243px;
    padding: 0 3px 2px 3px;
    line-height: 1.2;
    vertical-align: bottom;
  }
  .wide-field {
    display: inline-block;
    border-bottom: 1px solid black;
    width: 70%;
    padding: 1px 3px;
    margin-left: 10px;
  }

  .location-field {
    display: inline-block;
    border-bottom: 1px solid black;
    width: 70%;
    padding: 1px 3px;
    margin-left: 10px;
  }
  .line{
    border: 2px solid black;
    width: 100%;
    margin-top: 13px;
    margin-bottom: 8px;
  }
  .checkbox-label {
    vertical-align: middle;
    margin: 0;
  }
  .field_xxxl {
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

<h3>TAX DECLARATION OF REAL PROPERTY</h3>

<table>
  <tr>
    <td class="label">TD No.:
      <span class="field_lg"><?= $properties->arp_no ?></span>
    </td>
    <td>Property Identification No:
      <span class="field"><?= $properties->pin ?></span>
    </td>
  </tr>

  <tr>
    <td>OWNER:
      <span class="field_0lg"><?= $properties->owner ?></span>
    </td>
    <td >TIN:
      <span class="field"></span>
    </td>
  </tr>

  <tr>
    <td>ADDRESS:
      <span class="field"><?= $properties->property_address ?></span>
    </td>
    <td>TEL NO:
      <span class="field"></span>
    </td>
  </tr>

  <tr>
    <td>Administrator/Beneficial User:
      <span class="field"></span>
    </td>
    <td>TEL NO:
      <span class="field"></span>
    </td>
  </tr>

  <tr>
    <td>ADDRESS:
      <span class="field"></span>
    </td>
    <td>TEL NO:
      <span class="field"></span>
    </td>
  </tr>



  <tr>
    <td colspan="2">
      <span>Location of Property:</span>
      <span class="location-field"></span>
    </td>
  </tr>
</table>

<table>
  <tr>
    <td class="label">OCT/TCT/CLOA No:
      <span class="field">&nbsp;<?= $properties->otc ?></span>
    </td>
    <td >Survey No:
      <span class="field">&nbsp;<?= $properties->property_address ?></span>
    </td>
  </tr>

  <tr>
    <td>CCT:
      <span class="field">&nbsp;</span>
    </td>
    <td>Lot No
      <span class="field">&nbsp;<?= $properties->lot_number ?></span>
    </td>
  </tr>

  <tr>
    <td>Dated:
      <span class="field">&nbsp;</span>
    </td>
    <td>Blk. No.
      <span class="field">&nbsp;</span>
    </td>
  </tr>
</table>

<div>
  <span>Boundaries:</span>
</div>
<table>
  <tr>
    <td></td>
    <td>NORTH:
      <span >&nbsp;<?= $properties->north ?></span>
    </td>
    <td >EAST:
      <span >&nbsp;<?= $properties->east ?></span>
    </td>
  </tr>

  <tr>
    <td></td>
    <td>SOUTH:
      <span >&nbsp;<?= $properties->south ?></span>
    </td>
    <td >WEST
      <span >&nbsp;<?= $properties->west ?></span>
    </td>
  </tr>
  </tr>
</table>
<div class="line">

</div>
<div>
  <div style="margin-left: 4px; padding-bottom: 5px;" class="label">KIND OF PROPERTY ASSESSED:</div>
  <table>
    <tr>
      <td>
          <input type="checkbox" id="land" checked class="checkbox-label">
          <label for="Land" class="checkbox-label">Land</label>
      </td>
      <td>
          <input class="checkbox-label" type="checkbox" id="Machinery">
          <label for="Machinery" class="checkbox-label">Machinery</label>
          <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Brief Description: <span class="field" id="brief-description"></span></div>
      </td>
    </tr>
    <tr>
      <td>
        <input type="checkbox" id="building" class="checkbox-label">
        <label for="Building" class="checkbox-label">Building</label>
        <div>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          No. of Storey: <span id="story"></span>
        </div>
        <div>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          Breif Description: <span id="description" class="field"></span>
        </div>
      </td>
      <td>
        <input type="checkbox" id="others" class="checkbox-label">
        <label for="Othes" class="checkbox-label">Others</label>
        <div>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          Specify:<span id="other" class="field_md"></span>
        </div>
      </td>
    </tr>
  </table>
  <div style="margin-top: 30px;">
    <table>
      <tr>
        <td><span class="field_sm label text-center">Classification</span></td>
        <td><span class="field_sm label text-center">Area</span></td>
        <td><span class="field_sm label text-center">Market Value</span></td>
        <td><span class="field_sm label text-center">Actual Use</span></td>
        <td><span class="field_xs label text-center">Level</span></td>
        <td><span class="field_sm label text-center">Value</span></td>
      </tr>
      <tr>
        <td><span class="field_sm text-center"><?= $properties->property_classification ?? "" ?></span></td>
        <td><span class="field_sm text-center"><?= $properties->area ?>sq. m.</span></td>
        <td><span class="field_sm text-center">₱ <?= number_format(($properties->area * $properties->market_value_data), 2) ?></span></td>
        <td><span class="field_sm text-center"><?= $properties->ActualUse ?></span></td>
        <td><span class="field_xs text-center"><?= $properties->assessment_rate ?>%</span></td>
        <td><span class="field_sm text-center"> ₱ <?= number_format(($properties->area * $properties->market_value_data * ($properties->assessment_rate / 100)), 2) ?></span></td>
      </tr>
      <tr>
        <td><span class="field_sm mt-2 text-center"></span></td>
        <td><span class="field_sm mt-2 text-center"></span></td>
        <td><span class="field_sm mt-2 text-center"></span></td>
        <td><span class="field_sm mt-2 text-center"></span></td>
        <td><span class="field_xs mt-2 text-center"></span></td>
        <td><span class="field_sm mt-2 text-center"></span></td>
      </tr>
      <tr>
        <td><span class="field_sm mt-2 text-center"></span></td>
        <td><span class="field_sm mt-2 text-center"></span></td>
        <td><span class="field_sm mt-2 text-center"></span></td>
        <td><span class="field_sm mt-2 text-center"></span></td>
        <td><span class="field_xs mt-2 text-center"></span></td>
        <td><span class="field_sm mt-2 text-center"></span></td>
      </tr>
      <tr>
        <td><span></span></td>
        <td><span class="label">TOTAL</span></td>
        <td><span class="field_sm text-center">₱ <?= number_format(($properties->area * $properties->market_value_data), 2) ?></span></td>
        <td><span></span></td>
        <td><span></span></td>
        <td><span class="field_sm text-center">₱ <?= number_format(($properties->area * $properties->market_value_data * ($properties->assessment_rate / 100)), 2) ?></span></td>
      </tr>
    </table>
  </div>
  <div style="margin-top: 10px;">
    Total Assessed Value:
    <span class="field_xxl text-center">
    {{ convertNumberToWords($totalValue)}}
    </span>
    <span style="margin-left: 350px;">(Amount in Words)</span>
    <div style="margin-top: 30px;">
      <table>
        <tr>
          <td>
            <label for="Taxable" class="checkbox-label">Taxable</label>
            <input type="checkbox" id="Taxable" <?= $properties->istaxable == 0 ? " " : 'checked'?> class="checkbox-label">
          </td>
          <td>
            <label for="Exempt" class="checkbox-label">Exempt</label>
            <input type="checkbox" id="Exempt" <?= $properties->isexempted == 0 ? " " : 'checked'?> class="checkbox-label">
          </td>
          <td>
            Effectivity of Assessment/Reassessment
          </td>
          <td>
            <span class="field_xxs text-center"></span>
          </td>
          <td>
            <span class="field_xs text-center"></span>
          </td>
        </tr>
      </table>

    </div>

  </div>
  <div style="margin-top: 10px;">RECOMMENDED BY:</div>
  <table style="margin-top: 10px;">
    <tr>
      <td><span class="label">APPROVED BY:</span></td>
      <td>
        <span class="field_md text-center label"><?= $OIC_Municilap_Assessor->fullname ?></span>
      </td>
      <td>
        <span class="field_md text-center label"><?= $properties->fullname ?></span>
      </td>
      <td>
        <span class="field_xxs text-center label"><?= date("d/m/Y", strtotime(now())); ?></span>
      </td>
    </tr>
    <tr>
      <td><span></span></td>
      <td>
        <span class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OIC-Provincial Assessor</span>
      </td>
      <td>
        <span class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Municipal Assessor</span>
      </td>
      <td>
        <span class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date</span>
      </td>
    </tr>
  </table>

   <div class="mt-1">
    <span class="field_xxxl"></span>
    <span class="field_xxxl"></span>
    <span class="field_xxxl"></span>
    <span class="field_xxxl"></span>
    <span class="field_xxxl"></span>
  </div>
 <div class="mt-1" >
    <p style="font-size: 9px;">
      Note: This declaration is for real property taxation purposes only and the valuation indicated herein are based on the schedule of unit market values prepared for the purpose and duly enacted into an Ordinance by the Sangguniang ____________________ under Ordinance No. ______ dated ____, 20. It does not and cannot by itself alone confer any ownership or legal title to the property.
    </p>
  </div>
</div>
</body>
</html>
