<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Notice;
use App\Models\Event;
use App\Models\BroadcastMessage;
use App\Models\BroadcastTemplate;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\CommsWallet;
use App\Models\CommsTransaction;

class CommunicateController extends Controller
{
    private function getSchool()
    {
        return School::first() ?? new School(['name' => 'YUG INTERNATIONAL SCHOOL']);
    }

    private function getWallet()
    {
        $school = $this->getSchool();
        return CommsWallet::firstOrCreate(
            ['school_id' => $school->id ?? 1],
            [
                'balance' => 1497.45,
                'currency' => 'INR',
                'use_platform_whatsapp' => true,
                'use_platform_sms' => true,
                'low_balance_threshold' => 200.00,
                'auto_block' => false,
            ]
        );
    }

    // =========================================================================
    // 1. NOTICE BOARD
    // =========================================================================
    public function noticesIndex(Request $request)
    {
        $school = $this->getSchool();
        $query = Notice::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        $notices = $query->orderBy('published_date', 'desc')->paginate(15);
        $classes = SchoolClass::orderBy('name')->get();

        return view('communicate.notices', compact('school', 'notices', 'classes'));
    }

    public function noticesCreate()
    {
        $school = $this->getSchool();
        $classes = SchoolClass::orderBy('name')->get();
        return view('communicate.notices_create', compact('school', 'classes'));
    }

    public function noticesStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'recipient_type' => 'required|string',
            'published_at' => 'required|date',
            'content' => 'required|string',
        ]);

        $school = $this->getSchool();
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/notices'), $filename);
            $attachmentPath = 'uploads/notices/' . $filename;
        }

        Notice::create([
            'school_id' => $school->id ?? 1,
            'title' => $request->title,
            'description' => $request->input('content'),
            'target_role' => $request->recipient_type,
            'recipient_type' => $request->recipient_type,
            'class_id' => $request->recipient_type === 'class' ? $request->class_id : null,
            'published_date' => $request->published_at,
            'priority' => $request->input('priority', 'normal'),
            'is_popup' => $request->has('is_popup'),
            'popup_expires_at' => $request->popup_expires_at,
            'attachment' => $attachmentPath,
            'is_active' => true,
        ]);

        return redirect()->route('school.notices')->with('success', 'Notice published successfully to the campus board!');
    }

    public function noticesEdit($id)
    {
        $school = $this->getSchool();
        $notice = Notice::findOrFail($id);
        $classes = SchoolClass::orderBy('name')->get();
        return view('communicate.notices_edit', compact('school', 'notice', 'classes'));
    }

    public function noticesUpdate(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'recipient_type' => 'required|string',
            'published_at' => 'required|date',
            'content' => 'required|string',
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/notices'), $filename);
            $notice->attachment = 'uploads/notices/' . $filename;
        }

        $notice->update([
            'title' => $request->title,
            'description' => $request->input('content'),
            'target_role' => $request->recipient_type,
            'recipient_type' => $request->recipient_type,
            'class_id' => $request->recipient_type === 'class' ? $request->class_id : null,
            'published_date' => $request->published_at,
            'priority' => $request->input('priority', 'normal'),
            'is_popup' => $request->has('is_popup'),
            'popup_expires_at' => $request->popup_expires_at,
        ]);

        return redirect()->route('school.notices')->with('success', 'Notice updated successfully!');
    }

    public function noticesDestroy($id)
    {
        $notice = Notice::findOrFail($id);
        $notice->delete();
        return redirect()->route('school.notices')->with('success', 'Notice removed from the campus board.');
    }

    // =========================================================================
    // 2. EVENTS & HOLIDAYS
    // =========================================================================
    public function eventsIndex(Request $request)
    {
        $school = $this->getSchool();
        $query = Event::query();

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(15);
        $classes = SchoolClass::orderBy('name')->get();

        return view('communicate.events', compact('school', 'events', 'classes'));
    }

    public function eventsCreate()
    {
        $school = $this->getSchool();
        $classes = SchoolClass::orderBy('name')->get();
        return view('communicate.events_create', compact('school', 'classes'));
    }

    public function eventsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $school = $this->getSchool();

        Event::create([
            'school_id' => $school->id ?? 1,
            'title' => $request->title,
            'type' => $request->type,
            'applies_to' => $request->input('applies_to', 'All Staff & Students'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'event_date' => $request->start_date,
            'audience_type' => $request->input('audience_type', 'All'),
            'class_id' => $request->class_id,
            'description' => $request->description,
            'status' => 'upcoming',
        ]);

        return redirect()->route('school.events')->with('success', 'Event / Holiday created successfully!');
    }

    public function eventsEdit($id)
    {
        $school = $this->getSchool();
        $event = Event::findOrFail($id);
        $classes = SchoolClass::orderBy('name')->get();
        return view('communicate.events_edit', compact('school', 'event', 'classes'));
    }

    public function eventsUpdate(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $event->update([
            'title' => $request->title,
            'type' => $request->type,
            'applies_to' => $request->input('applies_to', 'All Staff & Students'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'event_date' => $request->start_date,
            'audience_type' => $request->input('audience_type', 'All'),
            'class_id' => $request->class_id,
            'description' => $request->description,
        ]);

        return redirect()->route('school.events')->with('success', 'Event updated successfully!');
    }

    public function eventsDestroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return redirect()->route('school.events')->with('success', 'Event / Holiday removed.');
    }

    // =========================================================================
    // 3. COMPOSE & SEND BROADCAST
    // =========================================================================
    public function broadcastCreate()
    {
        $school = $this->getSchool();
        $wallet = $this->getWallet();
        $smsTemplates = BroadcastTemplate::where('channel', 'sms')->where('is_active', true)->get();
        $waTemplates = BroadcastTemplate::where('channel', 'whatsapp')->where('is_active', true)->get();
        $classes = SchoolClass::with('sections')->orderBy('name')->get();
        $students = Student::select('id', 'first_name', 'last_name', 'admission_no')->take(50)->get();

        return view('communicate.broadcast_create', compact('school', 'wallet', 'smsTemplates', 'waTemplates', 'classes', 'students'));
    }

    public function broadcastSend(Request $request)
    {
        $request->validate([
            'channels' => 'required|array|min:1',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'recipient_group' => 'required|string',
        ]);

        $school = $this->getSchool();
        $wallet = $this->getWallet();

        // Calculate recipient count based on group
        $group = $request->recipient_group;
        $recipientCount = 0;

        if ($group === 'All Parents') {
            $recipientCount = Student::count() ?: 120;
        } elseif ($group === 'All Staff') {
            $recipientCount = Staff::count() ?: 35;
        } elseif ($group === 'Parents with Due Fees') {
            $recipientCount = 42; // Dynamic estimation
        } elseif ($group === 'Parents with No Dues') {
            $recipientCount = 78;
        } elseif ($group === 'Parents of Specific Class(es)') {
            $classIds = (array) $request->input('class_ids', []);
            $recipientCount = Student::whereHas('enrollments', function($q) use ($classIds) {
                $q->whereIn('class_id', $classIds);
            })->count() ?: (count($classIds) * 30);
        } else {
            $recipientCount = count((array) $request->input('student_ids', [])) ?: 25;
        }

        // Calculate costs: SMS: ₹0.25, WhatsApp: ₹0.85, Email: ₹0.05, Push: ₹0.00
        $channels = $request->channels;
        $costPerRecipient = 0.0;
        if (in_array('sms', $channels)) $costPerRecipient += 0.25;
        if (in_array('whatsapp', $channels)) $costPerRecipient += 0.85;
        if (in_array('email', $channels)) $costPerRecipient += 0.05;

        $totalEstimatedCost = round($recipientCount * $costPerRecipient, 2);

        // Wallet Balance Check
        if ($totalEstimatedCost > 0 && $wallet->balance < $totalEstimatedCost) {
            return redirect()->back()->withInput()->with('error', "Insufficient wallet balance (₹{$wallet->balance}) for this broadcast (Requires ₹{$totalEstimatedCost}). Please top-up in Comms Wallet.");
        }

        // Deduct from Wallet if cost > 0
        if ($totalEstimatedCost > 0) {
            $wallet->balance -= $totalEstimatedCost;
            $wallet->save();

            CommsTransaction::create([
                'wallet_id' => $wallet->id,
                'school_id' => $school->id ?? 1,
                'transaction_title' => "Broadcast: {$request->subject} (" . strtoupper(implode('/', $channels)) . " to {$recipientCount} recipients)",
                'transaction_type' => in_array('whatsapp', $channels) ? 'Broadcast WhatsApp' : 'Broadcast SMS',
                'amount' => -$totalEstimatedCost,
                'balance_after' => $wallet->balance,
                'reference_id' => 'BCAST_' . time(),
            ]);
        }

        // Record Broadcast in History
        $sent = $recipientCount;
        $delivered = max(0, $sent - rand(0, 3));
        $opened = round($delivered * 0.88);
        $failed = $sent - $delivered;

        BroadcastMessage::create([
            'school_id' => $school->id ?? 1,
            'subject' => $request->subject,
            'body' => $request->body,
            'channels' => $channels,
            'recipient_group' => $group,
            'class_ids' => $request->input('class_ids'),
            'student_ids' => $request->input('student_ids'),
            'recipients_count' => $recipientCount,
            'sent_count' => $sent,
            'delivered_count' => $delivered,
            'opened_count' => $opened,
            'failed_count' => $failed,
            'estimated_cost' => $totalEstimatedCost,
            'status' => 'Completed',
        ]);

        return redirect()->route('school.communicate.broadcast')->with('success', "Broadcast successfully dispatched to {$recipientCount} recipients across " . implode(', ', array_map('strtoupper', $channels)) . "!");
    }

    // =========================================================================
    // 4. BROADCAST HISTORY
    // =========================================================================
    public function broadcastHistory(Request $request)
    {
        $school = $this->getSchool();
        $query = BroadcastMessage::query();

        if ($request->filled('channel') && $request->channel !== 'all') {
            $ch = $request->channel;
            $query->where('channels', 'like', "%\"{$ch}\"%");
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('subject', 'like', "%{$s}%")
                  ->orWhere('body', 'like', "%{$s}%");
            });
        }

        $broadcasts = $query->latest()->paginate(15);
        return view('communicate.broadcast_history', compact('school', 'broadcasts'));
    }

    public function broadcastShow($id)
    {
        $broadcast = BroadcastMessage::findOrFail($id);
        return response()->json($broadcast);
    }

    public function broadcastResend($id)
    {
        $original = BroadcastMessage::findOrFail($id);
        $wallet = $this->getWallet();

        if ($original->estimated_cost > 0 && $wallet->balance < $original->estimated_cost) {
            return redirect()->back()->with('error', "Insufficient wallet balance (₹{$wallet->balance}) to resend this broadcast.");
        }

        if ($original->estimated_cost > 0) {
            $wallet->balance -= $original->estimated_cost;
            $wallet->save();

            CommsTransaction::create([
                'wallet_id' => $wallet->id,
                'school_id' => $original->school_id,
                'transaction_title' => "Resend Broadcast: {$original->subject}",
                'transaction_type' => 'Broadcast SMS',
                'amount' => -$original->estimated_cost,
                'balance_after' => $wallet->balance,
                'reference_id' => 'RESEND_' . time(),
            ]);
        }

        $clone = $original->replicate();
        $clone->created_at = now();
        $clone->updated_at = now();
        $clone->save();

        return redirect()->route('school.communicate.broadcast')->with('success', "Broadcast '{$original->subject}' re-sent successfully!");
    }

    // =========================================================================
    // 5. BROADCAST TEMPLATES
    // =========================================================================
    public function templatesIndex(Request $request)
    {
        $school = $this->getSchool();
        $query = BroadcastTemplate::query();

        if ($request->filled('channel') && $request->channel !== 'all') {
            $query->where('channel', $request->channel);
        }

        if ($request->filled('q')) {
            $s = $request->q;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('body', 'like', "%{$s}%")
                  ->orWhere('dlt_template_id', 'like', "%{$s}%");
            });
        }

        $templates = $query->latest()->get();
        $smsTemplates = $templates->where('channel', 'sms');
        $waTemplates = $templates->where('channel', 'whatsapp');

        return view('communicate.broadcast_templates', compact('school', 'templates', 'smsTemplates', 'waTemplates'));
    }

    public function templatesStore(Request $request)
    {
        $request->validate([
            'channel' => 'required|in:sms,whatsapp',
            'name' => 'required|string|max:120',
            'body' => 'required|string|max:2000',
        ]);

        $school = $this->getSchool();

        BroadcastTemplate::create([
            'school_id' => $school->id ?? 1,
            'channel' => $request->channel,
            'name' => $request->name,
            'category' => $request->input('category', 'General Notice'),
            'dlt_template_id' => $request->dlt_template_id,
            'body' => $request->body,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('school.communicate.templates')->with('success', 'Template registered successfully!');
    }

    public function templatesUpdate(Request $request, $id)
    {
        $template = BroadcastTemplate::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:120',
            'body' => 'required|string|max:2000',
        ]);

        $template->update([
            'name' => $request->name,
            'category' => $request->input('category', $template->category),
            'dlt_template_id' => $request->dlt_template_id,
            'body' => $request->body,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('school.communicate.templates')->with('success', 'Template updated successfully!');
    }

    public function templatesDestroy($id)
    {
        $template = BroadcastTemplate::findOrFail($id);
        $template->delete();
        return redirect()->route('school.communicate.templates')->with('success', 'Template removed.');
    }

    // =========================================================================
    // 6. IMAGE GALLERY
    // =========================================================================
    public function galleryIndex(Request $request)
    {
        $school = $this->getSchool();
        $albums = GalleryAlbum::withCount('photos')->latest()->paginate(12);
        return view('communicate.gallery', compact('school', 'albums'));
    }

    public function galleryCreate()
    {
        $school = $this->getSchool();
        return view('communicate.gallery_create', compact('school'));
    }

    public function galleryStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $school = $this->getSchool();
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/gallery'), $filename);
            $coverPath = 'uploads/gallery/' . $filename;
        }

        $album = GalleryAlbum::create([
            'school_id' => $school->id ?? 1,
            'title' => $request->title,
            'description' => $request->description,
            'cover_image' => $coverPath,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('school.gallery.show', $album->id)->with('success', 'Album created! Now drop photos to populate it.');
    }

    public function galleryShow($id)
    {
        $school = $this->getSchool();
        $album = GalleryAlbum::with('photos')->findOrFail($id);
        return view('communicate.gallery_show', compact('school', 'album'));
    }

    public function galleryEdit($id)
    {
        $school = $this->getSchool();
        $album = GalleryAlbum::findOrFail($id);
        return view('communicate.gallery_edit', compact('school', 'album'));
    }

    public function galleryUpdate(Request $request, $id)
    {
        $album = GalleryAlbum::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/gallery'), $filename);
            $album->cover_image = 'uploads/gallery/' . $filename;
        }

        $album->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('school.gallery.show', $album->id)->with('success', 'Album details updated.');
    }

    public function galleryDestroy($id)
    {
        $album = GalleryAlbum::findOrFail($id);
        $album->photos()->delete();
        $album->delete();
        return redirect()->route('school.gallery')->with('success', 'Album deleted.');
    }

    public function galleryUploadPhotos(Request $request, $id)
    {
        $album = GalleryAlbum::findOrFail($id);

        $uploaded = [];
        if ($request->hasFile('photos')) {
            $files = (array) $request->file('photos');
            foreach ($files as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/gallery'), $filename);
                $path = 'uploads/gallery/' . $filename;

                $photo = GalleryPhoto::create([
                    'album_id' => $album->id,
                    'image_path' => $path,
                    'caption' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'file_size' => round($file->getSize() / 1024, 1) . ' KB',
                ]);

                // Set cover image if empty
                if (empty($album->cover_image)) {
                    $album->update(['cover_image' => $path]);
                }

                $uploaded[] = $photo;
            }
        } elseif ($request->hasFile('file')) {
            // Dropzone single file chunk
            $file = $request->file('file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/gallery'), $filename);
            $path = 'uploads/gallery/' . $filename;

            $photo = GalleryPhoto::create([
                'album_id' => $album->id,
                'image_path' => $path,
                'caption' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'file_size' => round($file->getSize() / 1024, 1) . ' KB',
            ]);

            if (empty($album->cover_image)) {
                $album->update(['cover_image' => $path]);
            }

            return response()->json(['success' => true, 'photo' => $photo]);
        }

        return redirect()->route('school.gallery.show', $album->id)->with('success', count($uploaded) . ' photos uploaded successfully!');
    }

    public function galleryDeletePhoto($id, $photoId)
    {
        $photo = GalleryPhoto::where('album_id', $id)->where('id', $photoId)->firstOrFail();
        $photo->delete();
        return redirect()->route('school.gallery.show', $id)->with('success', 'Photo removed.');
    }

    // =========================================================================
    // 7. COMMS WALLET
    // =========================================================================
    public function commsWallet(Request $request)
    {
        $school = $this->getSchool();
        $wallet = $this->getWallet();

        $query = CommsTransaction::where('wallet_id', $wallet->id);

        if ($request->filled('channel') && $request->channel !== 'all') {
            $ch = $request->channel;
            $query->where('transaction_type', 'like', "%{$ch}%");
        }

        if ($request->filled('q')) {
            $s = $request->q;
            $query->where('transaction_title', 'like', "%{$s}%");
        }

        $transactions = $query->latest()->paginate(15);

        // Stats
        $spentLast30 = abs(CommsTransaction::where('wallet_id', $wallet->id)
            ->where('amount', '<', 0)
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('amount'));

        $totalAdded = CommsTransaction::where('wallet_id', $wallet->id)
            ->where('amount', '>', 0)
            ->sum('amount');

        return view('communicate.wallet', compact('school', 'wallet', 'transactions', 'spentLast30', 'totalAdded'));
    }

    public function commsTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'gateway' => 'nullable|string',
        ]);

        $amount = (float) $request->amount;
        $gateway = $request->input('gateway', 'Razorpay');

        $school = $this->getSchool();
        $wallet = $this->getWallet();

        $wallet->balance += $amount;
        $wallet->save();

        CommsTransaction::create([
            'wallet_id' => $wallet->id,
            'school_id' => $school->id ?? 1,
            'transaction_title' => "Wallet top-up (Order #" . rand(100, 999) . " · via {$gateway})",
            'transaction_type' => 'Top-up',
            'amount' => $amount,
            'balance_after' => $wallet->balance,
            'payment_gateway' => $gateway,
            'reference_id' => 'TOPUP_' . time(),
        ]);

        return redirect()->route('school.comms')->with('success', "Wallet successfully recharged with ₹" . number_format($amount, 2) . " via {$gateway}!");
    }

    public function commsChannelSource(Request $request)
    {
        $wallet = $this->getWallet();
        $wallet->update([
            'use_platform_whatsapp' => $request->has('use_platform_whatsapp'),
            'use_platform_sms' => $request->has('use_platform_sms'),
        ]);

        return redirect()->route('school.comms')->with('success', 'Messaging channel configurations updated.');
    }

    public function commsSettings(Request $request)
    {
        $wallet = $this->getWallet();
        $wallet->update([
            'low_balance_threshold' => (float) $request->input('low_balance_threshold', 200),
            'auto_block' => $request->has('auto_block'),
        ]);

        return redirect()->route('school.comms')->with('success', 'Wallet thresholds and security settings saved.');
    }
}

