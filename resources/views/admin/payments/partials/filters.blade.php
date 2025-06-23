<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="GET" action="{{ route('admin.payments.index') }}">
            <div class="modal-body">
                <h5 class="mb-3">Filter Pembayaran</h5>

                <div class="mb-3">
                    <label>Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                        <option value="challenge" {{ request('status') == 'challenge' ? 'selected' : '' }}>Challenge</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Metode</label>
                    <select class="form-select" name="method">
                        <option value="">Semua</option>
                        <option value="midtrans" {{ request('method') == 'midtrans' ? 'selected' : '' }}>Midtrans</option>
                        <option value="manual" {{ request('method') == 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Grup</label>
                    <select class="form-select" name="group">
                        <option value="">Semua Grup</option>
                        @foreach($groups as $id => $name)
                            <option value="{{ $id }}" {{ request('group') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Periode</label>
                    <input type="text" class="form-control" name="period" value="{{ request('period') }}">
                </div>

                <div class="mb-3">
                    <label>Cari (Nama/Email/ID)</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Dari Tanggal</label>
                        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Sampai Tanggal</label>
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">Reset</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Terapkan</button>
            </div>
        </form>
    </div>
</div>
