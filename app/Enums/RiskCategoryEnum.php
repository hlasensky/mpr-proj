<?php

namespace App\Enums;

enum RiskCategoryEnum:string
{
    case Low = "low";
    case Medium = "medium";
    case High = "high";
    case Danger = "danger";
    case Extreme = "extreme";
    public function color(): string
    {
        return match ($this) {
          self::Low => "green-400",
          self::Medium => "green-800",
          self::High => "yellow-800",
          self::Danger => "red-400",
          self::Extreme => "red-800",
        };
    }


}
