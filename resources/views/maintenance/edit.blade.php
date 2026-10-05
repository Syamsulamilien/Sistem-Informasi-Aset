<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-weight: 600; font-size: 20px; color: #1f2937;">
                {{ __('Edit Record Maintenance') }}
            </h2>
            <a href="{{ route('maintenance.index') }}" style="background-color: #d1d5db; color: #374151; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 500; transition: background-color 0.2s;"
               onmouseover="this.style.backgroundColor='#9ca3af'"
               onmouseout="this.style.backgroundColor='#d1d5db'">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div style="padding-top: 48px; padding-bottom: 48px;">
        <div style="max-width: 42rem; margin-left: auto; margin-right: auto; padding-left: 24px; padding-right: 24px;">
            <div style="background-color: white; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px;">
                <div style="padding: 24px;">
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