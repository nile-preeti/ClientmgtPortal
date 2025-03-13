<script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">

<style>
  .profile-records-body {
    box-shadow: 0 0 #0000, 0 0 #0000, 0px 12px 28px 0px rgba(36, 7, 70, .06);
    background: var(--white);
    border-radius: 10px;
    padding: 20px;
  }

  .profile-records-body h6 {
    margin: 0px;
    padding: 0px;

  }

  .accordion-button:not(.collapsed) {
    background: #064086 !important;
    color: #fff !important;
  }

  .table thead tr th {
    background: #dee2e6;
    color: #000000;
  }

  .subtotal-section {
    margin-left: 50px
  }

  .subtotal-section table {
    width: 100% !important;
  }

  .subtotal-section table tr td {
    border: 1px solid #dee2e6;
  }

  .accordion-button:not(.collapsed) {
    background: #fff;
  }

  .accordion {
    --bs-accordion-btn-focus-box-shadow: none !important;
  }

  .accordion-body {
    background: #f8fbfd;
  }

  .accordion-button::after {
    border-radius: 50px;
    padding: 10px !important;
    border: 5px solid #fff;
  }

  .accordion-button:not(.collapsed)::after {
    background-color: #fff;
    border: 6px solid #fff;
  }

  .accordion-header {
    margin-bottom: 0;
    padding: 0;
  }

  .btn-reloads {
    width: 43px !important;
    height: 39px;
    color: #3d3e3e;
    white-space: nowrap;
    background: #fff;
    box-shadow: 0px 8px 13px 0px rgba(35, 53, 111, 0.12);
    display: inline-block;
    text-align: center;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 600;
    border: 1px solid #3d3e3e;
    line-height: 40px;
  }


  .accordion-button:not(.collapsed) .subtotal-section table tr td {
    color: white !important;
  }

  .accordion-button:not(.collapsed) h6 {
    color: white !important;
  }
</style>

@extends('layouts.app')
@section('content')
<!-- Page Content  -->
<div id="content-page" class="content-page">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="iq-card">
          <!-- <div class="iq-card-header d-flex justify-content-between">
                                  <div class="iq-header-title">
                                     <h4 class="card-title">User List</h4>
                                  </div>
                             </div> -->
          <div class="iq-card-body">
            <form method="GET" action="{{ route('payouts.show', $user->id) }}">
              <div class="row mb-3">
                <div class="col-md-4">
                  <select name="month" class="form-control">
                    @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                      {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                    </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <select name="year" class="form-control">
                    @foreach(range(Carbon\Carbon::now()->year, Carbon\Carbon::parse(auth()->user()->created_at)->year) as $year)
                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                      {{ $year }}
                    </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-1">
                  <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                <div class="col-md-1 btn-reloads"
                  onclick="window.location.href = window.location.origin + window.location.pathname;" style="">
                  <img src="{{ asset('reset.png') }}" height="20" alt="">
                </div>
              </div>
            </form>
            <div class="profile-records-body">
              <div class="accordion" id="accordionExample">

                @foreach ($paginatedData as $index => $week)
                <div class="accordion-item">
                  <h2 class="accordion-header" id="heading{{ $index }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                      data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                      aria-controls="collapse{{ $index }}">
                      <div class="d-flex align-items-center">
                        <h6>
                          @if ($week['is_current_week'])
                          {{ $week['week_label'] }}
                          @else
                          {{ $week['week_label'] }}
                          @endif
                        </h6>
                        <div class="subtotal-section">
                          <table border="1" cellpadding="0" cellspacing="0" width="100%">
                            <tr height="50">
                              <td align="center" width="150" rowspan="2">Payout Total:</td>
                              <td align="center" width="200">Subtotal:</td>
                              <td align="center" width="200">Admin Fee:</td>
                              <td align="center" width="200">Employee Earnings:</td>
                              <td align="center" width="200">Payable Amount:</td>
                            </tr>
                            <tr height="50">
                              <td align="center" width="150">${{ $week['total_earnings'] }}</td>
                              <td align="center">${{ $week['admin_earnings'] }}</td>
                              <td align="center">${{ $week['user_earnings'] }}</td>
                              <td align="center">${{ $week['user_earnings'] }}</td>
                            </tr>
                          </table>
                        </div>
                      </div>
                    </button>
                  </h2>
                  <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                    aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Total Earnings</th>
                            <th scope="col">Admin Fee</th>
                            <th scope="col">Total Employee Earnings</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($week['daily_earnings'] as $day)
                          <tr>
                            <td>{{ \Carbon\Carbon::parse($day['date'])->format('M d, Y') }}</td>
                            <td>${{ number_format($day['earnings'], 2) }}</td>
                            <td>${{ number_format($day['admin_fee'], 2) }}</td>
                            <td>${{ number_format($day['user_earnings'], 2) }}</td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
              <div class="row d-flex justify-content-end mt-3">
                <div class="col-md-6">
                  <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-end mb-0">
                      @if ($paginatedData->onFirstPage())
                      <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                      </li>
                      @else
                      <li class="page-item">
                        <a class="page-link" href="{{ $paginatedData->previousPageUrl() }}">Previous</a>
                      </li>
                      @endif

                      @foreach ($paginatedData->links()->elements as $element)
                      @if (is_string($element))
                      <li class="page-item disabled"><a class="page-link">{{ $element }}</a></li>
                      @endif

                      @if (is_array($element))
                      @foreach ($element as $page => $url)
                      @if ($page == $paginatedData->currentPage())
                      <li class="page-item active"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                      @else
                      <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                      @endif
                      @endforeach
                      @endif
                      @endforeach

                      @if ($paginatedData->hasMorePages())
                      <li class="page-item">
                        <a class="page-link" href="{{ $paginatedData->nextPageUrl() }}">Next</a>
                      </li>
                      @else
                      <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
                      </li>
                      @endif
                    </ul>
                  </nav>
                </div>
              </div>



            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- Large Approved modal -->

@endsection
@push('js')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script> -->
@endpush