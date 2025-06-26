<?php

dataset('valid_moves', [
    'd4',
    'O-O',
    'e8=Q',
    'Rdf8',
    'gxh8=Q',
    'Nf6xe4',
    'Nf6xe4?',
    'Nf6xe4?!',
    'e4!!',
    'e4!?',
]);
dataset('invalid_moves', [
    'ee3',
    'j4',
    '!e3',
]);

dataset('valid_move_strings_for_supports', [
    '1. e4 c5 2. Nf3 d6 3. d4',
    '1. e4 c5 {This is a comment} 2. Nf3 d6 3. d4',
    '1. e4 c5 2. Nf3 d6 3. d4 cxd4 4. Nxd4 Nf6 5. Nc3 a6 6. Be2 e6 7. O-O Be7 8. f4 O-O 9. Kh1 Nc6 10. Be3 Qc7 11. a4 Bd7 12. Qe1 Nxd4 13. Bxd4 Bc6 14. Bd3 Nd7 15. Qg3 g6 16. f5 exf5 17. exf5 Ne5 18. Rae1 Rae8 19. Bxe5 dxe5 20. fxg6 hxg6 21. Bxg6 fxg6 22. Qxg6+ Kh8',
]);

dataset('invalid_move_strings_for_supports', [
    'e4 c5',
    '1. e4 j5',
]);

dataset('recombine_variations_and_comments', [
    [
        'input' => ['1.', 'e4', 'c5', '{This', 'is', 'a', 'comment}', '2.', 'Nf3', 'd6'],
        'expected' => ['1.', 'e4', 'c5', '{This is a comment}', '2.', 'Nf3', 'd6'],
    ],
    [
        'input' => ['1.', 'e4', 'c5', '{This', 'is', 'a', 'comment}', '(', 'e5', ')', '2.', 'Nf3', 'd6'],
        'expected' => ['1.', 'e4', 'c5', '{This is a comment}', '(e5)', '2.', 'Nf3', 'd6'],
    ],
]);
