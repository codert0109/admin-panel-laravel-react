@extends('layouts.admin.master')

@section('title', trans_choice('site.consultations', 1))

@section('css')
	<!-- Internal Data table css -->
	<link href="{{URL::asset('admin_assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('admin_assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
	<link href="{{URL::asset('admin_assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('admin_assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
	<link href="{{URL::asset('admin_assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
	<link href="{{URL::asset('admin_assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection

@section('page-header')
	<!-- breadcrumb -->
	<div class="breadcrumb-header justify-content-between">
		<div class="my-auto">
			<div class="d-flex">
				<h4 class="content-title mb-0 my-auto">{{ trans('site.table') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ trans_choice('site.consultations', 1) }}</span>
			</div>
		</div>
		<div class="d-flex my-xl-auto right-content">
			<div class="pr-1 mb-3 mb-xl-0">
				@can('consultation_create')
					<a href="{{ route('admin.consultations.create') }}" class="btn btn-sm btn-success"><i class="mdi mdi-plus"></i> {{ trans('site.add_new', ['attr' => removebeginninLetters(trans_choice('site.consultations', 0), 2)]) }}</a>
				@else
					<button type="button" class="btn btn-sm btn-success disabled"><i class="mdi mdi-plus"></i> {{ trans('site.add_new', ['attr' => removebeginninLetters(trans_choice('site.consultations', 0), 2)]) }}</button>
				@endcan
			</div>
			<div class="pr-1 mb-3 mb-xl-0">
				@can('client_create')
					<button type="button" class="btn btn-sm btn-warning modal-effect"  data-effect="effect-slide-in-right" data-toggle="modal" data-target="#addClient"
					data-url="{{ route('admin.clients.store') }}"
					data-submit_btn="{{ trans('site.add') }}"
					data-modal_title="{{ trans('site.add_new', ['attr' => removebeginninLetters(trans_choice('site.clients', 0), 2)]) }}"
					data-method=""><i class="mdi mdi-plus"></i> {{ trans('site.add_new', ['attr' => removebeginninLetters(trans_choice('site.clients', 0), 2)]) }}</button>
				@else
					<button type="button" class="btn btn-sm btn-warning disabled"><i class="mdi mdi-plus"></i> {{ trans('site.add_new', ['attr' => removebeginninLetters(trans_choice('site.clients', 0), 2)]) }}</button>
				@endcan
			</div>
		</div>
	</div>
	<!-- breadcrumb -->
@endsection

@section('content')
	@canany(['consultation_list','consultation_show','consultation_create','consultation_edit','consultation_delete'])
				<!-- row opened -->
				<div class="row row-sm">
					<div class="col-xl-12">
						@include('layouts.admin._partials.errors')
						<div class="card mg-b-20">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">
									<h4 class="card-title mg-b-0">{{ trans_choice('site.consultations', 1) }}</h4>
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>
								<p class="tx-12 tx-gray-500 mb-2">{{ trans('site.here_you_can',['attr' => trans_choice('site.consultations', 1)]) }}...
									@can('consultation_create')
										<a href="{{ route('admin.consultations.create') }}" class="btn btn-link btn-sm p-0"> {{ trans('site.add_new', ['attr' => removebeginninLetters(trans_choice('site.consultations', 0), 2)]) }}</a>
									@endcan
								</p>
							</div>
							<div class="card-body">
								@can('consultation_list')
									<table id="example" class="table table-striped dt-responsive text-center mg-b-0 text-md-nowrap" style="width:100%">
										<thead>
											<tr>
												<th class="border-bottom-0">{{ trans('site.consultation_file_number') }}</th>
												<th class="border-bottom-0">{{ trans('site.name_attr', ['attr' => trans_choice('site.clients', 0)]) }}</th>
												<th class="border-bottom-0">{{ trans('site.date_attr', ['attr' => trans_choice('site.consultations', 0)]) }}</th>
												<th class="border-bottom-0">{{ trans('site.amount_attr', ['attr' => trans_choice('site.consultations', 0)]) }}</th>
												<th class="border-bottom-0">{{ trans('site.amount_attr', ['attr' => trans('site.vat')]) }}</th>
												<th class="border-bottom-0">{{ trans('site.actions') }}</th>
												<th class="border-bottom-0">{{ trans('site.paid') }}</th>
												<th class="border-bottom-0">{{ trans('site.remaining') }}</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($consultations as $consultation)
											<tr>
												<td><a href="{{ (auth()->user()->can('consultation_show') ? route('admin.consultations.show', $consultation) : 'javascript:;') }}">{{ $consultation->consultation_number }}</a></td>
												<td><a href="{{ (auth()->user()->can('client_show') ? route('admin.clients.show', $consultation->client_id) : 'javascript:;') }}">{!! $consultation->client->trashed() ? '<span class="text-decoration-line-through text-muted">'.$consultation->client->name.'</span>' : $consultation->client->name !!}</a>
                                                    <p class="small text-muted">
                                                        @if ($consultation->client->trashed())
                                                            <span class="text-decoration-line-through text-muted">
                                                        @endif
                                                            {{ trans('site.phone_attr', ['attr' => '']) }}: <a href="{{ whatsappLink($consultation->client->mobile, 'مرحبا: '.$consultation->client->name) }}">{{ $consultation->client->mobile }}</a>
                                                        @if ($consultation->client->trashed())
                                                            </span>
                                                        @endif
                                                    </p>
                                                </td>
												<td>{{ $consultation->contract_date->format('Y-m-d') }}</td>
												<td>{{ $consultation->contract_amount }}</td>
												<td>{{ $consultation->VatValue }}
													<span class="d-block small text-warning font-weight-bold">{{ trans('site.contract_amount_including_tax') }}</span>
														{{ $consultation->total_amount }}
												</td>
												<td>
													<div class="btn-group">
														@can('consultation_show')
															<a href="{{ (auth()->user()->can('consultation_show') ? route('admin.consultations.show', $consultation) : 'javascript:;') }}" class="btn btn-sm btn-primary"><i class="las la-eye"></i></a>
														@else
															<button type="button" class="btn btn-sm btn-primary disabled"><i class="las la-eye"></i></button>
														@endcan

														@can('consultation_showContract')
															<a href="{{ route('admin.show.consultation.contract', $consultation) }}" target="_blank" class="btn btn-sm btn-success"><i class="fas fa-file-contract"></i></a>
														@else
															<button type="button" class="btn btn-sm btn-success disabled"><i class="fas fa-file-contract"></i></button>
														@endcan

														@can('consultation_edit')
															<a href="{{ route('admin.consultations.edit', $consultation) }}" class="btn btn-sm btn-info"><i class="las la-pen"></i></a>
														@else
															<button type="button" class="btn btn-sm btn-info disabled"><i class="las la-pen"></i></button>
														@endcan

														@can('consultation_delete')
															<button type="button" class="btn btn-sm btn-danger modal-effect"  data-effect="effect-slide-in-right" data-toggle="modal" data-target="#deleteItemModal"
															data-url="{{ route('admin.consultations.destroy', $consultation) }}"
															data-id="{{ $consultation->id }}"
															data-delete_label="{{ trans('site.sure_delete_attr', ['attr' => $consultation->consultation_number]) }}"
															data-modal_title="{{ trans('site.delete_attr', ['attr' => trans_choice('site.consultations', 0)]) }}"><i class="las la-trash"></i></button>
														@else
															<button type="button" class="btn btn-sm btn-danger disabled"><i class="las la-trash"></i></button>
														@endcan
													</div>
												</td>
												<td>{{ $consultation->clientAccounts->sum('credit') }}</td>
												<td>{{ $consultation->consultationRemaining }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								@else
									<div class="alert alert-warning" role="alert">{{ trans('site.user_does_not_have_the_right_permissions') }}</div>
								@endcan
							</div>
						</div>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->

		@can('consultation_delete')
			@include('admin._partials.delete_modal')
		@endcan

		@can('client_create')
			@include('admin.clients.partials.modal')
		@endcan
	@endcanany
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('admin_assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('admin_assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script>
	$(function(e) {
		$(function() {
			'use strict'

			@can('client_create')
				// hide modal with effect
				$('#addClient').on('shown.bs.modal', function(event) {
					var button = $(event.relatedTarget), // Button that triggered the modal
					url 			= button.data('url'),
					method 			= button.data('method'),
					submit_btn 		= button.data('submit_btn'),
					modal_title 	= button.data('modal_title'),

					modal = $(this);
					modal.find('#client_name').focus();
					modal.find('.modal-content').find('form').attr('action', url)
					modal.find('.modal-title').text(modal_title)
					modal.find('input[name=_method]').val(method)
					modal.find('.save-btn').text(submit_btn)

					if(submit_btn == "{{ trans('site.add') }}") {
						modal.find('.save-btn').removeClass('btn-primary btn-danger').addClass('btn-success');
					}
				});
			@endcan

			@can('consultation_delete')
				// hide modal with effect
				$('#deleteItemModal').on('shown.bs.modal', function(event) {
					var button 			= $(event.relatedTarget), // Button that triggered the modal
						url 			= button.data('url'),
						modal_title 	= button.data('modal_title'),
						id 				= button.data('id'),
						delete_label	= button.data('delete_label'),

					modal = $(this);
					modal.find('.modal-content').find('form').attr('action', url)
					modal.find('.modal-title').text(modal_title)
					modal.find('.delete_label').text(delete_label)
					modal.find('input[name=id]').val(id)
				});
			@endcan
		});
	});
</script>
@endsection
