<?php

namespace App\Http\Controllers;

use App\Models\Pelamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'skill' => 'required|string|max:255',
        ]);

        $pelamar = Auth::user()->pelamar;
        if ($pelamar) {
            $skills = $pelamar->skills ?? [];
            if (!in_array($request->skill, $skills)) {
                $skills[] = $request->skill;
                $pelamar->skills = array_values($skills);
                $pelamar->save();
            }
        }

        return redirect()->route('profile.index')->with('success', 'Skill berhasil ditambahkan');
    }

    public function update(Request $request, $id = null)
    {
        return redirect()->route('profile.index')->with('success', 'Skill berhasil diperbarui');
    }

    public function edit($id = null)
    {
        $pelamar = Auth::user()->pelamar;
        $skills = $pelamar->skills ?? [];
        $skillName = $skills[($id ?? 1) - 1] ?? 'Skill';
        $DS = (object)['id' => $id, 'skill' => $skillName, 'experience_level' => 'Menengah'];

        return view('non-user.profile.skill.edit', ['DS' => $DS]);
    }

    public function destroy($id = null)
    {
        $pelamar = Auth::user()->pelamar;
        if ($pelamar) {
            $skills = $pelamar->skills ?? [];
            if (isset($skills[($id ?? 1) - 1])) {
                unset($skills[($id ?? 1) - 1]);
                $pelamar->skills = array_values($skills);
                $pelamar->save();
            }
        }

        return redirect()->back()->with('success', 'Skill berhasil dihapus');
    }

    public function storeSuper(Request $request)
    {
        return redirect()->back()->with('success', 'Skill berhasil disimpan');
    }

    public function updateSuper(Request $request, $id = null)
    {
        return redirect()->back()->with('success', 'Data skill berhasil disimpan.');
    }

    public function editSuper($id = null)
    {
        $DS = (object)['id' => $id, 'skill' => 'Skill', 'experience_level' => 'Menengah'];
        return view('super_admin.pelamar.modal.edit.edit_skill', ['DS' => $DS]);
    }

    public function destroySuper($id = null)
    {
        return redirect()->back()->with('success', 'Skill berhasil dihapus');
    }
}
