<?php
namespace Database\Seeders;
use App\Models\{Theater,Seat,Showtime};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class FFCinemaTheatersSeeder extends Seeder
{
 public function run(): void {
  DB::transaction(function(){
   // Consolidate the two surplus demo halls only when their shows have no bookings.
   foreach ([9=>2,10=>3] as $sourceId=>$targetId) {
    $source=Theater::find($sourceId);
    if (!$source || !in_array($source->Theater_Name,['SKY Hall 05','SKY Hall 06'])) continue;
    $shows=Showtime::with('movie')->where('Theater_ID',$sourceId)->get();
    foreach($shows as $show){
     if($show->bookings()->exists()) throw new \RuntimeException('Cannot consolidate a hall with booked shows.');
     $start=strtotime($show->Show_Date.' '.$show->Show_Time);
     $end=$start+($show->movie->Duration+20)*60;
     foreach(Showtime::with('movie')->where('Theater_ID',$targetId)->get() as $other){
      $otherStart=strtotime($other->Show_Date.' '.$other->Show_Time);
      if($start<$otherStart+($other->movie->Duration+20)*60 && $otherStart<$end) throw new \RuntimeException('Hall consolidation would overlap showtimes.');
     }
     $show->update(['Theater_ID'=>$targetId]);
    }
    $source->seats()->delete();
    $source->delete();
   }
   for($number=1;$number<=8;$number++){
    $theater=Theater::updateOrCreate(['Theater_ID'=>$number],['Theater_Name'=>'โรง '.$number,'Theater_Location'=>'เซ็นทรัล ขอนแก่น']);
    $rows=$number===1?14:12; $columns=$number===1?22:18;
    for($row=1;$row<=$rows;$row++){
     $start=$row===1?3:($row===2?2:1); $end=$columns-($row===1?2:($row===2?1:0));
     for($col=$start;$col<=$end;$col++){
      if($theater->seats()->where('pos_x',$col)->where('pos_y',$row)->exists()) continue;
      Seat::firstOrCreate(['Theater_ID'=>$number,'Seat_No'=>chr(64+$row).$col],[
       'seat_row'=>chr(64+$row),'seat_number'=>$col,'pos_x'=>$col,'pos_y'=>$row,
       'Seat_Type'=>$row>=$rows-1?'VIP - 350 บาท':($row>=7?'Premium - 240 บาท':'Deluxe - 180 บาท')]);
     }
    }
   }
  });
 }
}
