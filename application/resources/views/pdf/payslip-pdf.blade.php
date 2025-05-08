<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Itemised Pay Slip</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	{{-- <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet"> --}}
	<style>
        @import url('https://fonts.googleapis.com/css2?family=Comic+Neue:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap');

		body {
			margin-top: 5%;
			margin-left: 6%;
			margin-right: 6%;
			margin-bottom: 5%;
			font-family: Arial, Helvetica, sans-serif;
			font-weight: 400;
			font-size: 25px;
		}

		table,
		.bordered-table-d {
			width: 100%;
		}

		.bordered-table-d {
			border-collapse: collapse;
		}


		.va-top {
			vertical-align: top;
		}

		.td-head {
			color: #ffffff;
			font-weight: bold;
			background: #f05b2c;
			border-radius: 15px;
			padding-top: 10px;
			padding-bottom: 10px;
			padding-left: 20px;
			padding-right: 20px;
		}

		.text-center-d {
			text-align: center;
		}

		.no-pm {
			margin: 0;
			padding: 0;
		}


		.bordered-table-d td,
		.bordered-table-d th {
			border: 1px solid black;
			border-collapse: collapse;
		}


		.bordered-table-d td {
			padding: 10px;
		}

		.blue-text {
			/* font-family: 'Comic Sans MS', cursive; */
			font-family: "Comic Neue", cursive;
			color: #32AADE;
			font-weight: bold;
		}

		.bg-new {
			background: #f05b2c;
			color: #ffffff;
			font-weight: bold;
			/* border-radius: 15px 0 0 0; */
		}

		.bg-second {
			background: #e6e7e9;
			/* border-radius: 15px 0 0 0; */
		}

		/* thi=s ci tbale c=s=s */
		.company-logo {
			max-width: 100%;
			height: auto;
			margin-bottom: 20px;
		}
	</style>
</head>

<?php

$url = url('/');
if (strpos($url, 'localhost') !== false) {
	$newUrl = str_replace('https://', 'http://', $url);
} else {
	$newUrl = str_replace('https://', 'https://', $url);
}

?>

<body>

	<table>
		<tbody>
			<tr>
				<td colspan="3" style="text-align: center;">			
                    <img src="{{$newUrl.'/hrms/uploads/company/'.$company_logo}}" alt="Company Logo" class="company-logo" style="width: 10%;">
				</td>
			</tr>

			<tr>
				<td></td>


				<td style="width: 8%;"></td>

				<td style="width: 46%;">
					<!-- <p style="font-size: 25px;pt-2">For the period:</p> -->
					<table>
						<tbody>

						</tbody>
					</table>
				</td>
			</tr>

			<!--    -->
			<tr class="va-top">
				<td>
					<p class="td-head">
						Name of Employer</p>
					<p class="blue-text" style="padding-left: 3%;"><?= $company_name ?></p>
				</td>
				<td></td>
				<td>
					<p class="td-head">Date of Payment</p>
					<p class="blue-text" style="padding-left: 3%;"><?= $date_of_payment ?></p>
				</td>
			</tr>


			<tr class="va-top">
				<td>
					<p class="td-head">
						Name of Employee</p>
					<p class="blue-text" style="padding-left: 3%; font-size: 28px;"><?= $name ?> (<?= $employee_id ?>)</p>
				</td>
				<td></td>
				<td>
					<p class="td-head">Mode of Payment</p>
					<p style="padding-left: 3%;"><span>Cash</span> / <span style="text-decoration:line-through;">Cheque</span> / <span style="text-decoration: line-through;">Bank Deposit</span></p>
				</td>
			</tr>

			<tr>
				<td class="va-top">
					<table class="bordered-table-d" style="font-size: 25px;">
						<tbody>
							<tr>
								<td class="bg-new">Item</td>
								<td class="bg-new" colspan="2">Amount</td>
							</tr>
							<tr>
								<td class="bg-second">Basic Pay</td>
								<td class="blue-text"><?= $basic ?></td>
								<td class="bg-second" style="width: 15%;text-align: center;">(A)</td>
							</tr>
							<tr>
								<td class="bg-second">Total Allowances<br>
									<span style="font-size: 20px; font-style: italic;">(Breakdown shown below)</span>
								</td>
								<td class="blue-text"><?= $allowance_total ?? 0 ?></td>
								<td class="bg-second" style="width: 15%;text-align: center;">(B)</td>
							</tr>

							<?php
							foreach ($allowance_array as $al): ?>

								<tr>
									<td style="height: 25px;"><?= $al->allowance_title ?></td>
									<td class="blue-text" colspan="2"><?= $al->allowance_amount ?></td>
								</tr>
							<?php endforeach; ?>
							<?php
							if ($general_amount): ?>
								<tr>
									<td style="height: 25px;">General Allowance</td>
									<td class="blue-text" colspan="2"><?= $general_amount ?></td>
								</tr>
							<?php endif; ?>
							<?php
							if ($motivation_amount): ?>
								<tr>
									<td style="height: 25px;">Motivation Incentive</td>
									<td class="blue-text" colspan="2"><?= $motivation_amount ?></td>
								</tr>
							<?php endif; ?>

							<tr>
								<td class="bg-second">Gross Pay (A + B)</td>
								<td class="blue-text"><?= floatval($basic) + floatval($allowance_total ?? 0) ?></td>
								<td class="bg-second" style="width: 15%;text-align: center;">(C)</td>
							</tr>
							<tr>
								<td style="height: 25px;"></td>
								<td class="blue-text" colspan="2"></td>
							</tr>
							<tr>
								<td class="bg-second">Total Deductions<br>
									<span style="font-size: 20px;font-style: italic;">(Breakdown shown below)</span>
								</td>
								<td class="blue-text"><?= $total_deduction ?? 0 ?></td>
								<td class="bg-second" style="width: 15%;text-align: center;">(D)</td>
							</tr>
							<?php
							if ($employee_deduction): ?><?php
														foreach ($employee_deduction as $sl_commissions): ?>
							<tr>

								<td style="height: 25px;"><?php echo $sl_commissions->name; ?></td>
								<td class="blue-text" colspan="2"><?php echo $sl_commissions->amount; ?></td>

							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php
					if ($statutory_deduction_amount): ?>
						<tr>
							<td>Statutory Deduction</td>
							<td class="blue-text" colspan="2"><?= $statutory_deduction_amount ?></td>
						</tr>
					<?php endif; ?>
					<tr>
						<td>Employee’s CPF Deduction</td>
						<td class="blue-text" colspan="2"><?= $cpf_employee ?></td>
					</tr>
					<tr>
						<td class="blue-text">Advanced Loan</td>
						<td class="blue-text" colspan="2"><?= $loan ?></td>
					</tr>
						</tbody>
					</table>
				</td>
				<td></td>
				<td class="va-top">
					<table class="bordered-table-d" style="font-size: 25px;">
						<tbody>
							<tr>
								<td colspan="3" class="bg-new">Overtime Details</td>
							</tr>
							<tr>
								<td class="bg-second">
									Overtime Payment Period(s)
								</td>
								<td class="blue-text" colspan="2">01-<?= $date_of_payment ?> to <?= date('d', strtotime('last day of previous month')) ?>-<?= $date_of_payment ?></td>
							</tr>
							<tr>
								<td class="bg-second">Overtime Hours Worked</td>
								<td class="blue-text" colspan="2"><?= $overtime_t ?? 0 ?></td>
							</tr>
							<tr>
								<td class="bg-second">Total Overtime Pay</td>
								<td class="blue-text"><?= $overtime ?? 0 ?></td>
								<td class="bg-second" style="width: 15%;text-align: center;">(E)</td>
							</tr>
							<tr>
								<td style="height: 25px;">Rest Day Pay</td>
								<td class="blue-text" colspan="2"><?= $rest_daya ?></td>
							</tr>
							<tr>
								<td class="bg-new"></td>
								<td class="blue-text" colspan="2" class="bg-new"></td>
							</tr>
							<tr>
								<td class="bg-second">Other Additional Payments<br>
									<span style="font-size: 20px;font-style: italic;">(Breakdown shown below)</span>
								</td>
								<td class="blue-text"><?= $claims + $other_payments_amount ?></td>
								<td class="bg-second" style="width: 15%;text-align: center;">(F)</td>
							</tr>

							<tr>
								<td style="height: 25px;">Annual Leave</td>
								<td colspan="2" class="bg-second"><?= $balance_leave ?></td>
							</tr>


							<tr>
								<td class="bg-second" style="height: 40px;">Net Pay (C-D+E+F)</td>
								<td colspan="2" class="blue-text colspan=" 2"><?= $total_pay ?></td>
							</tr>
							<tr>
								<td class="bg-second" style="height: 65px;">Employer’s CPF Contribution</td>
								<td colspan="2" class="blue-text colspan=" 2"><?= $cpf_employer ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</body>

</html>