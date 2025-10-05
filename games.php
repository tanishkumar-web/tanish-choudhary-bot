<?php
// games.php - Simple games for the Telegram bot

class BotGames {
    private $bot;
    
    public function __construct($bot) {
        $this->bot = $bot;
    }
    
    // Rock Paper Scissors game
    public function playRockPaperScissors($chatId, $userChoice = null) {
        if (!$userChoice) {
            // Send game options
            $text = "🎮 <b>Rock Paper Scissors</b>\n\n"
                  . "Choose your move:";
            
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => 'Rock 🪨', 'callback_data' => 'rps_rock'],
                        ['text' => 'Paper 📄', 'callback_data' => 'rps_paper'],
                        ['text' => 'Scissors ✂️', 'callback_data' => 'rps_scissors']
                    ]
                ]
            ];
            
            return $this->bot->sendMessage($chatId, $text, $keyboard, 'HTML');
        }
        
        // Game logic
        $choices = ['rock', 'paper', 'scissors'];
        $botChoice = $choices[array_rand($choices)];
        
        $emojis = [
            'rock' => '🪨',
            'paper' => '📄',
            'scissors' => '✂️'
        ];
        
        // Determine winner
        $result = $this->determineRPSWinner($userChoice, $botChoice);
        
        $messages = [
            'win' => "🎉 You Win!\n\nYou: {$emojis[$userChoice]}\nBot: {$emojis[$botChoice]}\n\nCongratulations!",
            'lose' => "😞 You Lose!\n\nYou: {$emojis[$userChoice]}\nBot: {$emojis[$botChoice]}\n\nBetter luck next time!",
            'draw' => "🤝 It's a Draw!\n\nYou: {$emojis[$userChoice]}\nBot: {$emojis[$botChoice]}\n\nGood game!"
        ];
        
        $text = $messages[$result];
        
        // Play again button
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Play Again', 'callback_data' => 'game_rps']
                ]
            ]
        ];
        
        return $this->bot->sendMessage($chatId, $text, $keyboard, 'HTML');
    }
    
    // Dice roll game
    public function rollDice($chatId) {
        $userRoll = rand(1, 6);
        $botRoll = rand(1, 6);
        
        $result = ($userRoll > $botRoll) ? 'win' : (($userRoll < $botRoll) ? 'lose' : 'draw');
        
        $messages = [
            'win' => "🎉 You Win!\n\nYou rolled: $userRoll\nBot rolled: $botRoll\n\nCongratulations!",
            'lose' => "😞 You Lose!\n\nYou rolled: $userRoll\nBot rolled: $botRoll\n\nBetter luck next time!",
            'draw' => "🤝 It's a Draw!\n\nYou rolled: $userRoll\nBot rolled: $botRoll\n\nGood game!"
        ];
        
        $text = $messages[$result];
        
        // Roll again button
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Roll Again', 'callback_data' => 'game_dice']
                ]
            ]
        ];
        
        return $this->bot->sendMessage($chatId, $text, $keyboard, 'HTML');
    }
    
    // Coin toss game
    public function tossCoin($chatId) {
        $result = (rand(0, 1) === 0) ? 'Heads' : 'Tails';
        $emoji = ($result === 'Heads') ? '👑' : '🔄';
        
        $text = "🪙 <b>Coin Toss</b>\n\n"
              . "Result: $result $emoji\n\n"
              . "Want to try again?";
        
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Toss Again', 'callback_data' => 'game_coin']
                ]
            ]
        ];
        
        return $this->bot->sendMessage($chatId, $text, $keyboard, 'HTML');
    }
    
    // Number guessing game
    public function playNumberGuess($chatId, $guess = null, $gameData = null) {
        if (!$gameData) {
            // Start new game
            $secretNumber = rand(1, 100);
            $attempts = 0;
            $gameData = [
                'number' => $secretNumber,
                'attempts' => $attempts,
                'min' => 1,
                'max' => 100
            ];
        }
        
        if ($guess === null) {
            // Send game instructions
            $text = "🔢 <b>Number Guessing Game</b>\n\n"
                  . "I'm thinking of a number between 1 and 100.\n"
                  . "Can you guess it?\n\n"
                  . "Send me a number between 1 and 100.";
            
            // Store game data in user's session (simplified)
            // In a real implementation, you would store this in a database
            $gameData['message'] = $text;
            return $gameData;
        }
        
        // Process guess
        $secretNumber = $gameData['number'];
        $attempts = $gameData['attempts'] + 1;
        
        if ($guess == $secretNumber) {
            $text = "🎉 <b>Correct!</b>\n\n"
                  . "You guessed the number $secretNumber in $attempts attempts!\n\n"
                  . "Great job!";
            
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => 'Play Again', 'callback_data' => 'game_guess']
                    ]
                ]
            ];
            
            return $this->bot->sendMessage($chatId, $text, $keyboard, 'HTML');
        } else if ($guess < $secretNumber) {
            $text = "📈 <b>Too Low!</b>\n\n"
                  . "Your guess: $guess\n"
                  . "Attempts: $attempts\n\n"
                  . "Try a higher number!";
        } else {
            $text = "📉 <b>Too High!</b>\n\n"
                  . "Your guess: $guess\n"
                  . "Attempts: $attempts\n\n"
                  . "Try a lower number!";
        }
        
        // Update game data
        $gameData['attempts'] = $attempts;
        $gameData['message'] = $text;
        return $gameData;
    }
    
    // Determine Rock Paper Scissors winner
    private function determineRPSWinner($userChoice, $botChoice) {
        if ($userChoice === $botChoice) {
            return 'draw';
        }
        
        $winningMoves = [
            'rock' => 'scissors',
            'paper' => 'rock',
            'scissors' => 'paper'
        ];
        
        return ($winningMoves[$userChoice] === $botChoice) ? 'win' : 'lose';
    }
}
?>