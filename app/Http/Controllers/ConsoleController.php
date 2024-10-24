<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\CommandEvent;
use App\Events\TypingEvent;
use App\Models\CommandHistory;
use Illuminate\Support\Facades\DB;

class ConsoleController extends Controller
{
    public function index()
    {
        $history = CommandHistory::orderBy('created_at', 'asc')->take(300)->get();
        return view('console', compact('history'));
    }

    public function sendCommand(Request $request)
    {
        $command = $request->command;

        $result = $this->executeCommand($command);
        $userId = $request->session()->getId();

        $this->storeCommand($command, $result);

        event(new CommandEvent($command, $result, $userId));

        return response()->json(['status' => 'success']);
    }

    public function sendTyping(Request $request)
    {
        $text = $request->text;
        $id = $request->session()->getId();

        event(new TypingEvent($text, $id));

        return response()->json(['status' => 'success']);
    }

    private function executeCommand($command)
    {
        $allowedCommands = ['help', 'date', 'whoami'];

        if (in_array($command, $allowedCommands)) {
            switch ($command) {
                case 'help':
                    return "Available commands: help, date, whoami";
                case 'date':
                    return date('D M d H:i:s Y');
                case 'whoami':
                    return 'user';
            }
        } else {
            return "bash: $command: command not found";
        }
    }

    private function storeCommand($command, $result)
    {
        $count = CommandHistory::count();
        if ($count >= 300) {
            CommandHistory::orderBy('created_at', 'asc')->first()->delete();
        }

        CommandHistory::create([
            'command' => $command,
            'result' => $result,
        ]);
    }
}