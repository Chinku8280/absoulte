<?php if ($this->input->get('ismobile') == 'true') { ?>
	<style>
		main {
			overflow-x: hidden;
			overflow-y: auto;
		}

		/* Circle */
		.progress-circle {
			width: 150px;
			height: 150px;
			border-radius: 50%;
			background: linear-gradient(90deg, #1C780D 70%, #CDD4D7 30%);
			position: relative;
			display: flex;
			justify-content: center;
			align-items: center;
			font-family: Arial, sans-serif;
			color: white;
		}



		.text {
			width: 130px;
			height: 130px;
			background-color: rgba(255, 255, 255, 1);
			border-radius: 50%;
			text-align: center;
			color: #000
		}

		.text p {
			font-size: 13px;
		}
	</style>
	<!--Content-->

	<?php if ($net_salary != 0 || $net_salary != '') : ?>
	<?php
		if ($wages_type == 1) {
			$bs = $basic_salary;
		} else {
			$bs = $daily_wages;
		}
		// $total_earning = $bs + $total_allowances + $overtime_amount + $commissions_amount + $other_payments_amount;
		$total_earning = $bs + $total_allowances + $total_overtime + $commissions_amount + $other_payments_amount;
		$total_deduction = floatval($loan_de_amount) + $statutory_deductions_amount;
		$total_net_salary = $total_earning - $total_deduction;
		// echo $total_overtime;
		//cpf
		if (isset($cpf_employee)) {
			$total_net_salary = $total_net_salary - $cpf_employee;
		}


		//contributions
		if (isset($contribution_fund)) {
			$total_contribution = 0;
			foreach ($contribution_fund as $cf) {
				$total_contribution += $cf['contribution_amount'];
			}
			$total_net_salary = $total_net_salary - $total_contribution;
		}

		//Leave Deduction
		if (isset($leave_deduction)) {
			$total_net_salary = $total_net_salary - $leave_deduction;
		}
	endif;
	?>
	<main class="py-3  mb-5 px-4">
		<section class="mt-3">
			<h4 class="theme-color text-uppercase text-center  my-0 py-0 " style="font-size: 24px; font-weight: 400;">
				payslip summary</h4>
			<h3 class="theme-color text-uppercase text-center  my-0 py-0 " style="font-size: 40px; font-weight: 500;">
				<span>&dollar;</span><?php echo $total_net_salary; ?>
			</h3>
			<p class="my-0 py-0 text-center " style="font-size: 15px; color:#565252">Net Pay, Jan 15th-Jan 25th, 2023
			</p>

			<!--Payroll-->

			<div class="theme-shadow rounded-3  mt-4" style="color: #484A4B;">
				<div class="bg-theme w-100 rounded-top-3 p-3">
					<p class="text-white my-auto text-center " style="font-size: 20px;">Details</p>
				</div>

				<div>

					<div class="row d-flex justify-content-center align-items-center px-2 py-4 ">
						<div class="col-6 d-flex justify-content-end m-0 p-1 ">
							<div class="progress-circle">
								<div class="text d-flex justify-content-center align-items-center ">
									<div class="my-auto ">
										<h3 class="theme-color m-0 p-0 " style="font-size: 20px; font-weight: 400;">
											<span>&dollar;</span><?php echo $gross_salary;?>
										</h3>
										<p class="theme-text-secondary m-0 p-0">Gross Pay</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-6 m-0  p-1">
							<div class="d-flex justify-content-start align-items-center ">
								<span class="p-2 bg-theme me-2"></span>Earning
							</div>
							<p class="ms-4"><span>&dollar;</span>3680.35</p>
							<div class="d-flex justify-content-start align-items-center ">
								<span class="p-2 me-2" style="background-color: #CDD4D7;"></span>Tax & Deduction
							</div>
							<p class="ms-4"><span>&dollar;</span>1000.00</p>
						</div>
					</div>


					<p class="text-start px-3 mx-0 my-auto text-uppercase theme-color" style="font-size: 17px ;font-weight: 400 ;font-weight: 400; ">Earning Details</p>

				</div>


				<!--Basic Pay-->
				<div class="d-inline-flex  justify-content-between align-items-center w-100 p-3 my-0 bg-white " style="border-bottom: 1px solid #484a4b3b;font-size: 15px;">

					<p class="text-start  mx-0 my-auto" style=" font-weight: 400 ;line-height: normal; ">Basic Pay</p>
					<p class="text-start my-auto  mx-0"><span>&dollar;</span><?php echo $basic_salary?></p>

				</div>

				<!--HRA-->
				<div class="d-inline-flex  justify-content-between align-items-center w-100 p-3 my-0 bg-white   " style="border-bottom: 1px solid #484a4b3b;font-size: 15px;">

					<p class="text-start  mx-0  my-auto p-0 " style=" font-weight: 400 ;line-height: normal; ">HRA</p>
					<p class="text-start my-auto  mx-0 p-0 "><span>&dollar;</span>500.00</p>

				</div>

				<!--Other Allowance-->
				<div class="d-inline-flex  justify-content-between align-items-center w-100 p-3 my-0 bg-white   " style="border-bottom: 1px solid #484a4b3b;font-size: 15px;">

					<p class="text-start  mx-0  my-auto p-0 " style=" font-weight: 400 ;line-height: normal; ">Other
						Allowance</p>
					<p class="text-start my-auto  mx-0 p-0 "><span>&dollar;</span>500.00</p>

				</div>

				<!--SPL Allowance-->
				<div class="d-inline-flex  justify-content-between align-items-center w-100 p-3 my-0 bg-white   " style="border-bottom: 1px solid #484a4b3b;font-size: 15px;">

					<p class="text-start  mx-0  my-auto p-0 " style=" font-weight: 400 ;line-height: normal; ">SPL
						Allowance</p>
					<p class="text-start my-auto  mx-0 p-0 "><span>&dollar;</span>680.00</p>

				</div>

				<!--Total Earning-->
				<div class="d-inline-flex  justify-content-between align-items-center w-100 p-3 my-0 bg-white rounded-bottom-3 theme-color " style="font-size: 15px;">

					<p class="text-start  mx-0  my-auto p-0 " style=" font-weight: 400 ;line-height: normal; ">Total
						Earning</p>
					<p class="text-start my-auto  mx-0 p-0 "><span>&dollar;</span>3680.00</p>

				</div>



			</div>
		</section>
	</main>

<?php }?>