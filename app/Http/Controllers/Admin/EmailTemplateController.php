<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::latest()->paginate(20);
        return view('admin.emails.templates.index', compact('templates'));
    }
    public function create()
    {
        return view('admin.emails.templates.form', ['template' => new EmailTemplate(['is_active' => true])]);
    }
    public function store(Request $request)
    {
        EmailTemplate::create($this->validated($request));
        return redirect()->route('admin.email-templates.index')->with('toast', '✅ টেমপ্লেট তৈরি হয়েছে');
    }
    public function edit(EmailTemplate $template)
    {
        return view('admin.emails.templates.form', compact('template'));
    }
    public function update(Request $request, EmailTemplate $template)
    {
        $template->update($this->validated($request, $template->id));
        return redirect()->route('admin.email-templates.index')->with('toast', '✅ টেমপ্লেট আপডেট হয়েছে');
    }
    public function destroy(EmailTemplate $template)
    {
        $template->delete();
        return back()->with('toast', 'টেমপ্লেট মুছে ফেলা হয়েছে');
    }
    private function validated(Request $r, $ignoreId = null): array
    {
        return $r->validate([
            'key' => 'required|string|max:80|unique:email_templates,key' . ($ignoreId ? ",{$ignoreId}" : ''),
            'name' => 'required|string|max:120',
            'subject' => 'required|string|max:200',
            'body' => 'required|string',
            'description' => 'nullable|string|max:500',
            'is_active' => 'sometimes|boolean',
        ]) + ['is_active' => $r->boolean('is_active')];
    }
}
