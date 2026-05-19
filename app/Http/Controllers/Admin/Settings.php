<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Settings extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = Setting::first();
        $pages = Page::where('status', 'active')->orderBy('created_at', 'asc')->get();
        
        // Decode JSON data if exists
        $footerQuickLinks = $settings && $settings->footer_quick_links 
            ? json_decode($settings->footer_quick_links, true) 
            : [];
        
        $headerMenus = $settings && $settings->header_menus 
            ? json_decode($settings->header_menus, true) 
            : [];
        
        $socialLinks = $settings && $settings->social_links 
            ? json_decode($settings->social_links, true) 
            : [];
        
        return view('admin.settings.index', compact(
            'settings', 
            'pages', 
            'footerQuickLinks', 
            'headerMenus', 
            'socialLinks'
        ));
    }

    /**
     * Save settings
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'site_title' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_mail' => 'required|email|max:255',
            'whatsapp_contact' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'footer_quick_links.*.title' => 'required|string|max:255',
            'footer_quick_links.*.slug' => 'required|string|max:255',
            'social_links.*.platform' => 'required|string|max:255',
            'social_links.*.url' => 'required|url|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please correct the errors below.');
        }

        try {
            DB::beginTransaction();

            $settings = Setting::firstOrCreate([]);

            // Handle file uploads
            $siteLogo = $this->handleFileUpload($request, 'site_logo', 'site-logo', $settings->site_logo);
            $favicon = $this->handleFileUpload($request, 'favicon', 'favicon', $settings->favicon);

            // Prepare JSON data
            $footerQuickLinks = $this->prepareJsonData($request->input('footer_quick_links', []));
            $socialLinks = $this->prepareJsonData($request->input('social_links', []));

            // Update settings
            $settings->update([
                'site_title' => $request->site_title,
                'site_description' => $request->site_description,
                'contact_mail' => $request->contact_mail,
                'whatsapp_contact' => $request->whatsapp_contact,
                'address' => $request->address,
                'site_logo' => $siteLogo,
                'favicon' => $favicon,
                'footer_quick_links' => json_encode($footerQuickLinks),
                'social_links' => json_encode($socialLinks),
            ]);

            DB::commit();

            return redirect()->route('admin.settings')
                ->with('success', 'Settings saved successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save settings. Please try again.');
        }
    }

    /**
     * Handle file upload
     */
    private function handleFileUpload($request, $fieldName, $folder, $oldFile = null)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $fileName = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Delete old file if exists
            if ($oldFile) {
                Storage::disk('public')->delete($folder . '/' . $oldFile);
            }
            
            // Store new file
            $file->storeAs('public/' . $folder, $fileName);
            
            return $fileName;
        }
        
        return $oldFile;
    }

    /**
     * Prepare JSON data by filtering empty values
     */
    private function prepareJsonData($data)
    {
        if (!is_array($data)) {
            return [];
        }

        return array_filter($data, function($item) {
            // Filter out items where all values are empty
            foreach ($item as $value) {
                if (!empty($value)) {
                    return true;
                }
            }
            return false;
        });
    }

    /**
     * Add dynamic form row (for AJAX)
     */
    public function addRow(Request $request)
    {
        $type = $request->type;
        $index = $request->index;
        
        if ($type === 'footer_quick_links') {
            $html = view('admin.settings.partials.footer-quick-link-row', [
                'index' => $index,
                'pages' => Page::where('status', 'active')->orderBy('created_at', 'asc')->get()
            ])->render();
            
            return response()->json(['html' => $html]);
        }
        
        if ($type === 'social_links') {
            $html = view('admin.settings.partials.social-link-row', [
                'index' => $index
            ])->render();
            
            return response()->json(['html' => $html]);
        }
        
        return response()->json(['html' => '']);
    }
}