@extends('layout.default')

@section('content')
<div class="container-fluid">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <h1 class="mb-4">Technician Payables</h1>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Installations</th>
                            <th>Installation Earnings</th>
                            <th>Package Earnings</th>
                            <th>Retained Customers</th>
                            <th>Retention Bonus</th>
                            <th>Total Earnings</th>
                            <th>Avg. Rating</th>
                            <th>Latest Comment</th>
                            <th>Paid Amount</th>
                            <th>Balance</th>
                            <th>Payment Status</th>


                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payables as $payable)
                        <tr>
                            <td>
                                {{ $payable['id'] }}</td>
                            <td>{{ $payable['name'] }}</td>
                            <td>{{ $payable['installationCount'] }}</td>
                            <td>{{ number_format($payable['installationEarnings'], 2) }} KSH</td>
                            <td>{{ number_format($payable['packageEarnings'], 2) }} KSH</td>
                            <td>{{ $payable['retainedCustomers'] }}</td>
                            <td>{{ number_format($payable['retentionBonus'], 2) }} KSH</td>
                            <td>{{ number_format($payable['totalEarnings'], 2) }} KSH</td>
                            <td>{{ number_format($payable['averageRating'], 2) }}</td>
                            <td>{{ Str::limit($payable['latestComment'], 30) }}</td>
                            <td>{{ number_format($payable['payment']->paid_amount ?? 0, 2) }} KSH</td>
                            <td>{{ number_format($payable['totalEarnings'] - ($payable['payment']->paid_amount ?? 0), 2)
                                }} KSH</td>



                            <!-- Payment Status -->
                            <td>
                                @if ($payable['installationCount'] > 0)
                                @if ($payable['averageRating'] < 3) <span class="badge badge-warning">Payment
                                    Held</span>
                                    @else
                                    <span class="badge badge-success">Eligible for Payment</span>
                                    @endif
                                    @else
                                    <span class="badge badge-secondary">No Installations</span>
                                    @endif
                            </td>

                            <!-- Action -->
                            <td>
                                @php
                                $paymentStatus =
                                DB::table('payments')->where('technician_id',$payable['id'])->value('status') ??
                                'Pending';


                                @endphp
                                @if ($payable['installationCount'] > 0)
                                <span
                                    class="badge badge-{{ $paymentStatus == 'Completed' ? 'success' : ($paymentStatus == 'Partial' ? 'warning' : 'secondary') }}">
                                    {{ $paymentStatus }}
                                </span>
                                @endif
                                @if ($payable['installationCount'] == 0)
                                <span class="badge badge-secondary">No Installations</span>
                                @endif


                                @if ($payable['installationCount'] > 0 && $payable['averageRating'] >= 3 &&
                                $paymentStatus != 'Completed')
                                <!-- Button to Open Modal -->
                                @php
                                $balance = $payable['totalEarnings'] - ($payable['payment']->paid_amount ?? 0);
                                @endphp

                                @if ($balance > 0)
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                    data-target="#payModal-{{ $payable['id'] }}">
                                    Pay
                                </button>
                                @endif


                                <!-- Payment Confirmation Modal -->
                                <div class="modal fade" id="payModal-{{ $payable['id'] }}" tabindex="-1" role="dialog"
                                    aria-labelledby="payModalLabel-{{ $payable['id'] }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="payModalLabel-{{ $payable['id'] }}">Confirm
                                                    Payment
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('pay.technician', $payable['id']) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p>Total Amount: KES {{ number_format($payable['totalEarnings'], 2)
                                                        }}</p>
                                                    <p>Balance: KES {{ number_format($payable['totalEarnings'] -
                                                        ($payable['payment']->paid_amount ?? 0), 2) }}</p>
                                                    <div class="form-group">
                                                        <label for="paymentAmount-{{ $payable['id'] }}">Payment
                                                            Amount</label>
                                                        <input type="number" class="form-control"
                                                            id="paymentAmount-{{ $payable['id'] }}"
                                                            name="payment_amount"
                                                            value="{{ $payable['totalEarnings'] - ($payable['payment']->paid_amount ?? 0) }}"
                                                            min="0"
                                                            max="{{ $payable['totalEarnings'] - ($payable['payment']->paid_amount ?? 0) }}"
                                                            step="0.01" required>
                                                        <input type="hidden" value="{{ $payable['totalEarnings'] }}"
                                                            name="totalEarnings">
                                                        <input type="hidden"
                                                            value="{{ $payable['payment']->paid_amount ?? 0 }}"
                                                            name="paidAmount">
                                                        <input type="hidden"
                                                            value="{{ $payable['totalEarnings'] - ($payable['payment']->paid_amount ?? 0) }}"
                                                            name="balance">
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Confirm
                                                        Payment</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $payables->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Revision Modal -->
<div class="modal fade" id="revisionModal" tabindex="-1" role="dialog" aria-labelledby="revisionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="revisionModalLabel">Request Revision</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('request.revision', '') }}" method="POST" id="revisionForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="revisionMessage">Message for Technician:</label>
                        <textarea class="form-control" id="revisionMessage" name="message" rows="3" required></textarea>
                    </div>
                    <input type="hidden" name="technician_id" id="technicianId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            const revisionButtons = document.querySelectorAll('.request-revision');
            const revisionModal = new bootstrap.Modal(document.getElementById('revisionModal'));
            const revisionForm = document.getElementById('revisionForm');
            const technicianIdInput = document.getElementById('technicianId');

            revisionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const technicianId = this.getAttribute('data-id');
                    technicianIdInput.value = technicianId;
                    revisionForm.action = "{{ route('request.revision', '') }}/" + technicianId;
                    revisionModal.show();
                });
            });
        });
</script>
@endsection
