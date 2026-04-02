<?php

namespace App\Ai\Agents;
 
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

#[MaxTokens(1000)]
#[Model('gemini-2.5-flash')]
#[Provider(Lab::Gemini)]
#[Temperature(0.7)]
class SupportAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * The list of messages comprising the conversation so far.
     *
     * @var Message[]
     */
    protected array $history = [];

    /**
     * Create a new agent instance.
     *
     * @param  Message[]  $history
     */
    public function __construct(array $history = [])
    {
        $history = array_map(function ($message) {
            if (is_array($message)) {
                return new Message($message['role'], $message['content']);
            }
            return $message;
        }, $history);

        $this->history = $history;
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
        Anda adalah asisten support untuk aplikasi Laravel AI.
        Tugas Anda adalah menjawab pertanyaan user berdasarkan riwayat percakapan.
        
        Aturan:
        1. Jawab dengan singkat, ramah, dan profesional.
        2. Jika tidak tahu jawabannya, katakan: "Maaf, saya tidak tahu jawabannya."
        3. Jangan pernah membuat informasi yang tidak ada.
        4. Selalu gunakan bahasa Indonesia.
        PROMPT;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return $this->history;
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }
}
