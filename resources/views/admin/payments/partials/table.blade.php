<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Grup</th>
                        <th>Peserta</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td><code>{{ $payment->midtrans_order_id ?? $payment->id }}</code></td>
                            <td>{{ $payment->group->name }}</td>
                            <td>
                                <strong>{{ $payment->user->name }}</strong><br>
                                <small>{{ $payment->user->email }}</small>
                            </td>
                            <td>{{ $payment->period }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                @if($payment->payment_method === 'midtrans')
                                    <span class="badge bg-info"><i class="fas fa-credit-card"></i> Midtrans</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-hand-holding-usd"></i> Manual</span>
                                @endif
                            </td>
                            <td>
                                @switch($payment->payment_status)
                                    @case('paid')
                                        <span class="badge bg-success">Lunas</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                        @break
                                    @case('failed')
                                        <span class="badge bg-danger">Gagal</span>
                                        @break
                                    @case('challenge')
                                        <span class="badge bg-info">Challenge</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $payment->payment_status }}</span>
                                @endswitch
                            </td>
                            <td>
                                {{ $payment->created_at->format('d M Y') }}<br>
                                <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    @if($payment->payment_status === 'pending')
                                        @if($payment->payment_method === 'midtrans' && $payment->snap_token)
                                            <button class="btn btn-sm btn-primary" onclick="openMidtransPayment('{{ $payment->snap_token }}')"><i class="fas fa-credit-card"></i></button>
                                        @endif
                                        <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success" onclick="return confirm('Verifikasi pembayaran ini?')"><i class="fas fa-check"></i></button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus pembayaran ini?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i><br>
                                Tidak ada pembayaran ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    </div>
</div>
