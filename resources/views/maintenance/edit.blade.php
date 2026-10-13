<x-app-layout :title="'Edit Record Maintenance'">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('maintenance.index') }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors shadow-sm" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Edit Record Maintenance</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui data atau status pemeliharaan aset</p>
                </div>
            </div>
            <a href="{{ route('maintenance.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
            <div class="p-6">
                    <form method="POST" action="{{ route('maintenance.update', $maintenance) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Asset Selection -->
                        <div style="margin-bottom: 24px;">
                            <label for="asset_id" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                                Aset <span style="color: #dc2626;">*</span>
                            </label>
                            <select name="asset_id" id="asset_id" required
                                    style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)'"
                                    @error('asset_id') style="border-color: #ef4444;" @enderror>
                                <option value="">Pilih Aset</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}" 
                                            {{ (old('asset_id', $maintenance->asset_id) == $asset->id) ? 'selected' : '' }}>
                                        {{ $asset->label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('asset_id')
                                <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Schedule Date -->
                        <div style="margin-bottom: 24px;">
                            <label for="schedule_date" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                                Tanggal Terjadwal <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="date" name="schedule_date" id="schedule_date" required
                                   value="{{ old('schedule_date', $maintenance->schedule_date->format('Y-m-d')) }}"
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                                   onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                                   onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)'"
                                   @error('schedule_date') style="border-color: #ef4444;" @enderror>
                            @error('schedule_date')
                                <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Performed Date -->
                        <div style="margin-bottom: 24px;">
                            <label for="performed_date" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                                Tanggal Pelaksanaan <span style="color: #9ca3af;">(Opsional)</span>
                            </label>
                            <input type="date" name="performed_date" id="performed_date"
                                   value="{{ old('performed_date', $maintenance->performed_date?->format('Y-m-d')) }}"
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                                   onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                                   onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)'"
                                   @error('performed_date') style="border-color: #ef4444;" @enderror>
                            @error('performed_date')
                                <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                            @enderror
                            <p style="margin-top: 4px; font-size: 12px; color: #6b7280;">Kosongkan jika belum dilaksanakan</p>
                        </div>

                        <!-- Tanggal Penerimaan Barang -->
                        <div style="margin-bottom: 24px;">
                            <label for="tanggal_penerimaan_barang" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                                Tanggal Penerimaan Barang <span style="color: #9ca3af;">(Opsional)</span>
                            </label>
                            <input type="date" name="tanggal_penerimaan_barang" id="tanggal_penerimaan_barang"
                                   value="{{ old('tanggal_penerimaan_barang', $maintenance->tanggal_penerimaan_barang?->format('Y-m-d')) }}"
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                                   onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                                   onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)'"
                                   @error('tanggal_penerimaan_barang') style="border-color: #ef4444;" @enderror>
                            @error('tanggal_penerimaan_barang')
                                <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                            @enderror
                            <p style="margin-top: 4px; font-size: 12px; color: #6b7280;">Tanggal barang diterima kembali setelah maintenance</p>
                        </div>

                        <!-- ✅ PERBAIKAN: Technician Selection - Tidak wajib -->
                        <div style="margin-bottom: 24px;">
                            <label for="technician_id" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                                Teknisi <span style="color: #9ca3af;">(Opsional)</span>
                            </label>
                            <select name="technician_id" id="technician_id"
                                    style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)'"
                                    @error('technician_id') style="border-color: #ef4444;" @enderror>
                                <option value="">Pilih Teknisi (Opsional)</option>
                                @foreach ($technicians as $technician)
                                    <option value="{{ $technician->id }}" 
                                            {{ (old('technician_id', $maintenance->technician_id) == $technician->id) ? 'selected' : '' }}>
                                        {{ $technician->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('technician_id')
                                <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                            @enderror
                            <p style="margin-top: 4px; font-size: 12px; color: #6b7280;">Pilih teknisi jika sudah ditentukan</p>
                        </div>

                        <!-- ✅ TAMBAHAN: Technician Name Manual -->
                        <div style="margin-bottom: 24px;">
                            <label for="technician_name" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                                Atau Masukkan Nama Teknisi Manual <span style="color: #9ca3af;">(Opsional)</span>
                            </label>
                            <input type="text" name="technician_name" id="technician_name"
                                   value="{{ old('technician_name', $maintenance->technician_name) }}"
                                   placeholder="Masukkan nama teknisi eksternal..."
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                                   onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                                   onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)'"
                                   @error('technician_name') style="border-color: #ef4444;" @enderror>
                            @error('technician_name')
                                <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                            @enderror
                            <p style="margin-top: 4px; font-size: 12px; color: #6b7280;">Gunakan ini jika teknisi tidak terdaftar di sistem</p>
                        </div>

                        <!-- Cost -->
                        <div style="margin-bottom: 24px;">
                            <label for="cost" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                                Biaya (Rp)
                            </label>
                            <input type="number" name="cost" id="cost" step="0.01" min="0"
                                   value="{{ old('cost', $maintenance->cost) }}"
                                   placeholder="0.00"
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                                   onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                                   onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)'"
                                   @error('cost') style="border-color: #ef4444;" @enderror>
                            @error('cost')
                                <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div style="margin-bottom: 24px;">
                            <label for="status" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                                Status <span style="color: #dc2626;">*</span>
                            </label>
                            <select name="status" id="status" required
                                    style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)'"
                                    @error('status') style="border-color: #ef4444;" @enderror>
                                <option value="Scheduled" {{ old('status', $maintenance->status) == 'Scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                <option value="Proses" {{ old('status', $maintenance->status) == 'Proses' ? 'selected' : '' }}>Proses</option>
                                <option value="Completed" {{ old('status', $maintenance->status) == 'Completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="Cancelled" {{ old('status', $maintenance->status) == 'Cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div style="margin-bottom: 24px;">
                            <label for="notes" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                                Catatan
                            </label>
                            <textarea name="notes" id="notes" rows="4"
                                      placeholder="Masukkan catatan maintenance, masalah yang ditemukan, suku cadang yang diganti, dll..."
                                      style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-family: Arial, sans-serif; resize: vertical;"
                                      onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                                      onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)'"
                                      @error('notes') style="border-color: #ef4444;" @enderror>{{ old('notes', $maintenance->notes) }}</textarea>
                            @error('notes')
                                <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div style="display: flex; justify-content: flex-end; gap: 12px;">
                            <a href="{{ route('maintenance.index') }}" 
                               style="background-color: #d1d5db; color: #374151; padding: 8px 24px; border-radius: 6px; text-decoration: none; font-weight: 500; transition: background-color 0.2s; display: inline-block;"
                               onmouseover="this.style.backgroundColor='#9ca3af'"
                               onmouseout="this.style.backgroundColor='#d1d5db'">
                                Batal
                            </a>
                            <button type="submit" 
                                    style="background-color: #2563eb; color: white; padding: 8px 24px; border-radius: 6px; border: none; font-weight: 500; cursor: pointer; transition: background-color 0.2s;"
                                    onmouseover="this.style.backgroundColor='#1d4ed8'"
                                    onmouseout="this.style.backgroundColor='#2563eb'">
                                Update Record Maintenance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <script>
        // Auto-set performed date ketika status diubah ke Completed
        document.getElementById('status').addEventListener('change', function() {
            const performedDateInput = document.getElementById('performed_date');
            if (this.value === 'Completed' && !performedDateInput.value) {
                performedDateInput.value = new Date().toISOString().split('T')[0];
            }
        });
    </script>
</x-app-layout>