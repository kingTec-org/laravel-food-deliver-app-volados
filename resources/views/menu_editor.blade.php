@extends('template')

@section('main')
<main id="site-content" role="main">
	<div class="partners">
		@include ('partner_navigation')
		<div class="menu-editor mt-md-4 mb-5">
			<h1>Menu Editor</h1>
			<div class="mt-4 mb-5 panel-content">
				<div class="d-md-flex align-items-center justify-content-between">
					<h2>Craft your menu</h2>
					<p class="required-b">Pending changes</p>
				</div>
				<div class="menu-container row m-0 mt-4">
					<div class="col-md-6 col-lg-3 d-md-flex align-items-end flex-column p-0">
						<ul class="menu-list">
							<li class="active">
								<a href="javascript:void(0)">
									<i class="icon icon-angle-arrow-pointing-to-right-1 mr-2"></i>
									Brunch
								</a>
								<div class="tooltip-link">
									<a href="javascript:void(0)" class="icon icon-question-mark"></a>
									<div class="tooltip-content">
										<a href="javascript:void(0)" data-toggle="modal" data-target="#edit_modal">
											<i class="icon icon-pencil-edit-button"></i>
											Edit
										</a>
										<a href="javascript:void(0)" data-toggle="modal" data-target="#menu_category_model">
											<i class="icon icon-copy"></i>
											Duplicate
										</a>
										<a href="javascript:void(0)" data-toggle="modal" data-target="#delete_modal" class="category_delete">
											<i class="icon icon-rubbish-bin"></i>
											Delete
										</a>
									</div>
								</div>
								<div class="sub-menu-list">
									<ul>
										<li>
											<a href="javascript:void(0)">Most Popular</a>
										</li>
										<li>
											<a href="javascript:void(0)">Brunch Menu
												<i data-toggle="modal" data-target="#sub_edit_modal" class="icon icon-pencil-edit-button float-right"></i>
											</a>
										</li>
										<li>
											<a href="javascript:void(0)">Sides</a>
										</li>
									</ul>
									<a href="javascript:void(0)" class="text-uppercase theme-color">add category</a>
								</div>
							</li>
							<li>
								<a href="javascript:void(0)">Most Popular</a>
							</li>
							<li>
								<a href="javascript:void(0)">Brunch Menu</a>
							</li>
							<li>
								<a href="javascript:void(0)">Sides</a>
							</li>
							<li>
								<a href="javascript:void(0)">Juices</a>
							</li>
							<li>
								<a href="javascript:void(0)">Burgers</a>
							</li>
						</ul>
						<div class="w-100 mt-auto pt-4">
							<button type="button" class="theme-color text-uppercase bg-white text-center w-100">Add Category</button>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 d-md-flex align-items-end flex-column p-0 mt-5 mt-md-0">
						<ul class="menu-list">
							<li>
								<a href="javascript:void(0)">Protein Smoothie</a>
							</li>
							<li>
								<a href="javascript:void(0)">Immune Builder</a>
							</li>
							<li>
								<a href="javascript:void(0)">Hamburger</a>
							</li>
						</ul>
						<div class="w-100 mt-auto pt-4 text-md-right text-lg-left">
							<button type="button" class="theme-color bg-white text-center text-uppercase w-100">Add Item</button>
						</div>
					</div>
					<div class="col-md-12 col-lg-6 d-md-flex align-items-end flex-column p-0 mt-5 mt-lg-0">
						<div class="panel-content w-100">
							<h2>Protein Smoothie</h2>
							<div class="item-info py-3">
								<p>16 oz smoothie with banana, blueberries, coconut milk, almond butter, hemp protein and milk.</p>
							</div>
							<div class="row my-3">
								<div class="col-md-4">
									<label>Price</label>
									<input type="text" name="" value="$8.00">
								</div>
								<div class="col-md-4 mt-3 mt-md-0">
									<label>Tax</label>
									<input type="text" name="" value="8.75%">
								</div>
							</div>
							<div class="modifiers mt-4">
								<h4>Add modifiers</h4>
								<div class="d-md-flex">
									<div class="select w-100">
										<select>
											<option>select a modifier</option>
											<option>Choice of Bun</option>
											<option>Add Protein</option>
											<option>Add Sides</option>
										</select>
									</div>
									<a href="javascript:void(0)" data-toggle="modal" data-target="#add_modal" class="btn btn-theme mt-2 mt-md-0">ADD</a>
								</div>
								<div class="menu-group mt-4">
									<div id="accordion" class="menu-accordion">
										<div class="card">
											<div class="card-header" id="headingOne">
												<button class="" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
													<i class="icon icon-angle-arrow-pointing-to-right-1 theme-color mr-2"></i>Add-ons
												</button>
											</div>
											<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
												<div class="card-body row m-0">
													<div class="col-md-6">
														<h4>Item</h4>
														<ul>
															<li>Add Cheese</li>
															<li>Add Lettuce</li>
															<li>Add Tomato</li>
														</ul>
													</div>
													<div class="col-md-6">
														<h4>Additional price</h4>
														<ul>
															<li>$1.00</li>
															<li>$2.00</li>
															<li>$3.00</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id="accordion" class="menu-accordion">
										<div class="card">
											<div class="card-header" id="headingTwo">
												<button class="collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseOne">
													<i class="icon icon-angle-arrow-pointing-to-right-1 theme-color mr-2"></i>Substitutions
												</button>
											</div>
											<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
												<div class="card-body row m-0">
													test
												</div>
											</div>
										</div>
									</div>
									<a href="javascript:void(0)" class="theme-color mt-4 d-block">ADD MODIFIER GROUP</a>
								</div>
							</div>
						</div>
						<div class="w-100 text-right mt-auto pt-4">
							<button type="button" class="btn btn-theme w-100 text-uppercase">Submit Changes</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<div class="modal fade" id="edit_modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="icon icon-close-2"></i>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group d-flex menu-name">
						<input class="pl-0" type="text" name="" value="Brunch" readonly/>
						<i class="icon icon-pencil-edit-button"></i>
					</div>
					<!-- <div class="menu-available">
						<p>When is this menu available?</p>
						<div class="d-md-flex">
							<div class="select w-50 mr-2">
								<select>
									<option>Every day</option>
									<option>Monday</option>
									<option>Tuesday</option>
									<option>Wednesday</option>
								</select>
							</div>
							<div class="added-times d-flex ml-3 align-items-center">
								<input type="text" name="" value="5:00 PM" readonly>
								<span class="d-inline-block mx-2">to</span>
								<input type="text" name="" value="5:00 PM" readonly>
								<a href="javascript:void(0)" class="icon icon-close-2 d-inline-block ml-2 theme-color"></a>
							</div>
						</div>
						<a href="javascript:void(0)" class="theme-color text-uppercase d-block mt-3">
							<i class="icon icon-add mr-3"></i>
							add more
						</a>
					</div> -->
					<div class="mt-3 pt-4 modal-footer px-0 text-right">
						<button type="reset" class="btn btn-primary theme-color">CANCEL</button>
						<button type="submit" class="btn btn-theme ml-2">SUBMIT</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="sub_edit_modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="icon icon-close-2"></i>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group d-flex menu-name">
						<input class="pl-0" type="text" name="" value="Brunch" readonly/>
						<i class="icon icon-pencil-edit-button"></i>
					</div>
					<div class="mt-3 pt-4 modal-footer px-0 text-right">
						<button type="reset" class="btn btn-primary theme-color">CANCEL</button>
						<button type="submit" class="btn btn-theme ml-2">SUBMIT</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="delete_modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="icon icon-close-2"></i>
				</button>
				<h3 class="modal-title">Delete this category?</h3>
			</div>
			<div class="modal-body">
				<p>This will delete Burgers and all off its items. This action cannot be undone.</p>
			</div>
			<div class="modal-footer text-right">
				<button type="reset" class="btn btn-primary theme-color">CANCEL</button>
				<button type="submit" class="btn btn-theme ml-2">SUBMIT</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add_modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="icon icon-close-2"></i>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group d-flex menu-name">
						<input class="pl-0 light-color" type="text" name="" value="Choice of Toppings" readonly/>
						<i class="icon icon-pencil-edit-button"></i>
					</div>
					<div class="menu-available">
						<p>How many items can the customer choose?</p>
						<div class="d-md-flex">
							<div class="select w-50 mr-2">
								<select>
									<option>Select a range</option>
									<option>1</option>
									<option>2</option>
									<option>3</option>
								</select>
							</div>
							<div class="added-times d-flex ml-3 align-items-center">
								<input type="text" name="" value="0" readonly>
								<span class="d-inline-block mx-2">to</span>
								<input type="text" name="" value="5">
							</div>
						</div>
						<div class="my-4 required-list">
							<p class="mb-1">Is this required?</p>
							<ul>
								<li>
									<label>
										<input type="radio" name="">
										Required
									</label>
								</li>
								<li>
									<label>
										<input type="radio" name="">
										Optional
									</label>
								</li>
							</ul>
						</div>
						<div class="add-list-row row">
							<div class="col-6">
								<label>Item</label>
								<input type="text" value="Add Cheese" />
							</div>
							<div class="col-6">
								<label>Optional fee</label>
								<input type="text" value="$1.00" />
							</div>
						</div>
						<div class="added-list-row my-4">
							<div class="row">
								<div class="col-6">
									<input type="text" value="Add Cheese" />
								</div>
								<div class="col-6 d-flex align-items-center">
									<input type="text" value="$1.00" />
									<i class="icon icon-rubbish-bin ml-2"></i>
								</div>
							</div>
						</div>
						<a href="javascript:void(0)" class="theme-color text-uppercase d-block mt-3">
							<i class="icon icon-add mr-3"></i>
							add another item
						</a>
					</div>
					<div class="mt-3 pt-4 modal-footer px-0 text-right">
						<button type="reset" class="btn btn-primary theme-color">CANCEL</button>
						<button type="submit" class="btn btn-theme ml-2">SUBMIT</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop