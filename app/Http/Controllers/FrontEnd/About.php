<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Careers;
use App\Models\Contact;
use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class About extends Controller
{
    public $SectionRecord = [];
    public $pageRecord;
    public $slug;
    public $submenuSlug;
    public $childSlug;
    public $page_type = 'about';
    public function index($slug = null,$submenuSlug = null,$childSlug = null)
    {
        
        $pageSlug = $childSlug ?? $submenuSlug ?? $slug;
        try {

            if (request()->segment(1) == 'board') {
                $page_type = 'board';
                $board = DB::table('boards')->where('slug', $pageSlug)->first();
                //   DD($board);
                $pageRecord = DB::table('tabs')->where('board_id', $board->id)->get();
                if (!$pageRecord) {
                    return view('livewire.front-end.error');
                } else {
                    return view('livewire.front-end.board', [
                        'pageRecord' => $pageRecord,
                        'board' => $board,
                        'page_type' => $page_type
                    ]);
                }
            }
            $pageRecord = DB::table('pages')->where('slug', $pageSlug)->first();
            // dd($this->pageRecord);
            if (!$pageRecord) {
                return view('livewire.front-end.error');
            } else {

                if (!$pageSlug) {
                    return view('livewire.front-end.error');
                }
                $SectionRecord = DB::table('pages')
                    ->where('pages.slug', $pageSlug)
                    ->leftJoin('page_section', 'pages.id', '=', 'page_section.page_id')
                    ->select('pages.*', 'page_section.*')
                    ->get();
                return view('front-end.about',
                [
                        'pageRecord' => $pageRecord,
                        'page_type' => 'about',
                        'SectionRecord' => $SectionRecord
                    ]
            );
            }
        } catch (\Exception $e) {
        }
    }
    public function __construct($slug = null, $submenuSlug = null, $childSlug = null)
    {
        $this->slug = $slug;
        $this->submenuSlug = $submenuSlug;
        $this->childSlug = $childSlug;
    }
    public function ContactRequest(Request $request)
    {

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string',  'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Contact::class],
        ]);
        try {
            $data = [
                'name' => $validated['name'] ?? '',
                'email' => $validated['email'] ?? '',
                'message' => $validated['message'] ?? '',
                'type' => $request->type ?? 'newletter',
            ];

            DB::table('contacts')->insert($data);
            $user = DB::table('contacts')->where('email', $data['email'])->first();

            $token = Str::random(64);
            if ($data['type'] == 'newletter') {
                FacadesMail::send('emails.newLetterEmail', ['user' => $user, 'token' => $token], function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Newsletter / Monthly Update');
                });
            } else {
                FacadesMail::send('emails.contactEmail', ['user' => $user, 'token' => $token], function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Contact Mail');
                });
            }
            return redirect()->back()->with('success', 'Your message has been sent successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while processing your request.');
        }
    }
    public function scholarships(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'policy' => ['required'],
            'salary_slip' => ['required'],
            'father_name' => ['required', 'string', 'max:255'],
            'contact_no' => ['required'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'parents_id_card' => ['required'],
            'electricity_bills' => ['required'],
            'academic_transcripts' => ['required'],
            'parental_bank_certificate' => ['required'],
            'message' => ['required', 'string',  'max:255'],
        ]);
        try {
            $data = [
                'name' => $validated['name'],
                'father_name' => $validated['father_name'],
                'contact_no' => $validated['contact_no'],
                'email' => $validated['email'],
                'salary_slip' => $validated['salary_slip'],
                'policy' => $validated['policy'],
                'parents_id_card' => $validated['parents_id_card'],
                'electricity_bills' => $validated['electricity_bills'],
                'academic_transcripts' => $validated['academic_transcripts'],
                'parental_bank_certificate' => $validated['parental_bank_certificate'],
                'message' => $validated['message'],
            ];
            $policy = 0;
            if ($data['policy'] == "on") {
                $policy = 1;
            } else {
                $policy = 0;
            }
            $parents_id_card = $data['parents_id_card'] ? $this->storeImage($data['parents_id_card']) : null;
            $electricity_bills = $data['electricity_bills'] ? $this->storeImage($data['electricity_bills']) : null;
            $academic_transcripts = $data['academic_transcripts'] ? $this->storeImage($data['academic_transcripts']) : null;
            $parental_bank_certificate = $data['parental_bank_certificate'] ? $this->storeImage($data['parental_bank_certificate']) : null;

            $record =  DB::table('scholarship_requests')->insert([
                'name' => $data['name'],
                'father_name' => $data['father_name'],
                'contact_no' => $data['contact_no'],
                'salary_slip' => $data['salary_slip'],
                'policy' => $policy,
                'email' => $data['email'],
                'parents_id_card' => $parents_id_card,
                'electricity_bills' => $electricity_bills,
                'academic_transcripts' => $academic_transcripts,
                'parental_bank_certificate' => $parental_bank_certificate,
                'message' => $data['message'],
            ]);


            $lastId = DB::getPdo()->lastInsertId();

            if ($request->achievements_and_awards) {
                foreach ($request->achievements_and_awards as $item) {
                    $achievement = $item ? $this->storeImage($item) : null;
                    DB::table('scholarship_request_achievements')->insert([
                        'scholarship_request_id' => $lastId,
                        'achievement' => $achievement,
                    ]);
                }
            }

            $user = DB::table('scholarship_requests')->where('email', $data['email'])->first();

            $token = Str::random(64);
            FacadesMail::send('emails.scholarshipEmail', ['user' => $user, 'token' => $token], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Scholarship Mail');
            });

            return redirect()->back()->with('success', 'Your message has been sent successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while processing your request.');
        }
    }

    public function careers(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'policy' => ['required'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Careers::class],
            'position' => ['required'],
            'contact_no' => ['required'],
            'cover_letter' => ['required', 'string'],
            'resume' => ['required'],
        ]);
        try {
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'policy' => $validated['policy'],
                'position' => $validated['position'],
                'contact_no' => $validated['contact_no'],
                'cover_letter' => $validated['cover_letter'],
                'resume' => $validated['resume'],
            ];
            $policy = 0;
            if ($data['policy'] == "on") {
                $policy = 1;
            } else {
                $policy = 0;
            }
            $resume = $data['resume'] ? $this->storeImage($data['resume']) : null;


            $record =  DB::table('careers')->insert([
                'name' => $data['name'],
                'email' => $data['email'],
                'policy' => $policy,
                'position' => $data['position'],
                'contact' => $data['contact_no'],
                'resume' => $resume,
                'cover_letter' => $data['cover_letter'],
                'created_at' => now(),
            ]);

            $user = DB::table('careers')->where('email', $data['email'])->first();

            $token = Str::random(64);
            FacadesMail::send('emails.careerEmail', ['user' => $user, 'token' => $token], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Career Mail');
            });
            return redirect()->back()->with('success', 'Your message has been sent successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while processing your request.');
        }
    }

    private function storeImage($image)
    {
        $ext = $image->getClientOriginalExtension();
        $newName = time() . '-' . rand(1000, 1000000) . '.' . $ext;
        $image->storeAs('', $newName);
        return $newName;
    }
    public function CustomQuery(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'board' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'array'],
            'subject.*' => ['string', 'max:255'],
            'message' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:custom_query,email'],
        ]);

        try {
            $data = [
                'name' => $validated['name'],
                'level' => $validated['level'],
                'board' => $validated['board'],
                'subject' => json_encode($validated['subject']),
                'email' => $validated['email'],
                'message' => $validated['message'],
                'created_at' => now()
            ];

            DB::table('custom_query')->insert($data);

            return redirect()->back()->with('success', 'Your message has been sent successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while processing your request.');
        }
    }



   
}
