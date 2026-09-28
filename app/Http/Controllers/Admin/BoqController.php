<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boq;
use Illuminate\Http\Request;

class BoqController extends Controller
{
   public function index(Request $request)
{
    $search        = $request->filled('search')         ? $request->search         : null;
    $tanggalDari   = $request->filled('tanggal_dari')   ? $request->tanggal_dari   : null;
    $tanggalSampai = $request->filled('tanggal_sampai') ? $request->tanggal_sampai : null;
    $sort          = $request->filled('sort')           ? $request->sort           : 'tanggal_boq';
    $dir           = $request->filled('dir') && in_array($request->dir, ['asc','desc'])
                        ? $request->dir : 'desc';

    // Whitelist kolom biar aman dari SQL injection
    $allowedSort = ['tanggal_boq', 'nomor_boq', 'created_at'];
    if (!in_array($sort, $allowedSort)) $sort = 'tanggal_boq';

    $boq = Boq::withCount('details')
        ->when($search, function ($q) use ($search) {
            $q->where('nomor_boq', 'ILIKE', "%{$search}%");
        })
        ->when($tanggalDari, function ($q) use ($tanggalDari) {
            $q->whereDate('tanggal_boq', '>=', $tanggalDari);
        })
        ->when($tanggalSampai, function ($q) use ($tanggalSampai) {
            $q->whereDate('tanggal_boq', '<=', $tanggalSampai);
        })
        ->orderBy($sort, $dir)
        ->paginate(10)
        ->withQueryString();

    return view('admin.boq.index', compact(
        'boq', 'search', 'tanggalDari', 'tanggalSampai', 'sort', 'dir'
    ));
}

    public function show(string $id)
    {
        $boq = Boq::with(['details.produk.brand', 'details.produk.unit'])
            ->findOrFail($id);

        return view('admin.boq.show', compact('boq'));
    }

    public function destroy(string $id)
    {
        $boq = Boq::findOrFail($id);

        // Hapus detail dulu (kalau gak pakai cascadeOnDelete)
        $boq->details()->delete();

        $boq->delete();

        return redirect()->route('admin.boq.index')
            ->with('success', 'BOQ berhasil dihapus.');
    }
}