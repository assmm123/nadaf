<?php

namespace App\Livewire;

use App\Models\ChatLog;
use App\Models\ChatQuestion;
use App\Models\CommunicationMethod;
use Livewire\Component;

/** الشات العائم — أجوبة جاهزة يديرها الأدمن 100% (بلا ذكاء اصطناعي) */
class FloatingChat extends Component
{
    public bool $open = false;

    public string $message = '';

    public array $messages = [];

    public bool $showContact = false;

    public string $sessionId;

    public function mount()
    {
        $this->sessionId = (string) (session('floating_chat_session') ?? session()->getId());
        session(['floating_chat_session' => $this->sessionId]);

        if (session()->has('payment_error_count') && session('payment_error_count') >= 2) {
            $this->open = true;
            $this->messages[] = [
                'from' => 'bot',
                'text' => 'لاحظنا أنك واجهت صعوبة في إتمام الدفع. اختر سؤالًا من القائمة أو اكتب استفسارك وسنساعدك فورًا.',
            ];
        }
    }

    public function toggle()
    {
        $this->open = ! $this->open;

        if ($this->open && count($this->messages) === 0) {
            $this->messages[] = [
                'from' => 'bot',
                'text' => 'مرحبًا بك في متجر نداف 👋\nكيف نقدر نساعدك؟ اكتب سؤالك أو اختر من الأوامر:',
                'suggestions' => ChatQuestion::where('is_active', true)->orderBy('sort_order')->take(4)->pluck('question')->all(),
            ];
        }
    }

    public function ask(string $question = '')
    {
        $text = trim($question !== '' ? $question : $this->message);
        $this->message = '';

        if ($text === '') {
            return;
        }

        $this->messages[] = ['from' => 'user', 'text' => $text];

        $match = ChatQuestion::match($text);

        // هل سأل سؤالاً مشابهاً سابقاً؟ (إذا تكرر السؤال مرتين نعرض وسائل التواصل فوراً)
        $similarCount = ChatLog::where('session_id', $this->sessionId)
            ->where('created_at', '>=', now()->subHours(12))
            ->get()
            ->filter(function (ChatLog $log) use ($text) {
                similar_text(mb_strtolower($log->message), mb_strtolower($text), $pct);

                return $pct >= 65;
            })
            ->count();
        $repeated = $similarCount >= 1; // المرسل الآن = المرة الثانية (أو أكثر)

        ChatLog::create([
            'session_id' => $this->sessionId,
            'visitor_name' => auth()->user()?->name,
            'message' => $text,
            'matched_answer' => $match?->answer,
            'was_helpful' => $match !== null,
            'trigger' => 'user',
        ]);

        if ($match && ! $repeated) {
            $this->messages[] = ['from' => 'bot', 'text' => $match->answer];
        } elseif ($match && $repeated) {
            // السؤال مكرر — الجواب الآلي لم يكفي، انقل الزائر للتواصل المباشر
            $this->showContact = CommunicationMethod::where('is_active', true)->exists();
            $this->messages[] = [
                'from' => 'bot',
                'text' => "لاحظت أنك تكررت سؤالك — يبدو أن الجواب العام لم يكفي.\nدعنا نتكلم مباشرة، اختر الوسيلة الأنسب لك وسنرد فورًا:",
                'show_contact' => true,
            ];
        } else {
            $this->showContact = CommunicationMethod::where('is_active', true)->exists();
            $this->messages[] = [
                'from' => 'bot',
                'text' => 'لم أجد جوابًا جاهزًا لسؤالك — تواصل معنا مباشرة عبر إحدى الوسائل التالية وسنرد بأسرع وقت:',
                'show_contact' => true,
            ];
        }
    }

    public function render()
    {
        return view('livewire.floating-chat');
    }
}
