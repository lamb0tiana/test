<?php

namespace App\TypeDefinition;


enum FieldType: int
{
    case Text = 0;
    case Textarea = 1;
    case Number = 2;
    case Date = 3;
    case Select = 4;
    case Boolean = 5;
    case Email = 6;
}
