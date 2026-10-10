@php($restore = $record ? old('_record') == $record->Movie_ID : !old('_method'))
@foreach(['Title'=>['ชื่อเรื่อง (อังกฤษ) *','text'],'Thai_Title'=>['ชื่อเรื่อง (ไทย)','text'],'Release_Date'=>['วันที่เข้าฉาย','date'],'Duration'=>['ความยาว (นาที)','number'],'Genre'=>['ประเภทภาพยนตร์','text'],'Director'=>['ผู้กำกับ','text'],'Actors'=>['นักแสดง','text']] as $field=>$meta)
<label>{{ $meta[0] }}<input class="cinema-input" name="{{ $field }}" type="{{ $meta[1] }}" value="{{ $restore ? old($field,$record?->$field) : $record?->$field }}" @required($field==='Title') @if($field==='Duration') min="1" @endif></label>
@endforeach
<div x-data="{preview:null, change(event){if(this.preview) URL.revokeObjectURL(this.preview); this.preview=event.target.files[0] ? URL.createObjectURL(event.target.files[0]) : null}, destroy(){if(this.preview) URL.revokeObjectURL(this.preview)}}">
<label>อัปโหลดโปสเตอร์<input class="cinema-input" type="file" name="poster_file" accept="image/jpeg,image/png,image/webp" @change="change($event)"></label>
<p class="muted small" style="margin-top:6px">JPG, PNG หรือ WebP ขนาดไม่เกิน 2 MB{{ $record ? ' · ไม่เลือกไฟล์ใหม่จะใช้รูปเดิม' : '' }}</p>
@if($restore && $errors->has('poster_file'))<p class="text-red-600" role="alert">{{ $errors->first('poster_file') }}</p>@endif
<img x-cloak x-show="preview" :src="preview" alt="ตัวอย่างโปสเตอร์ที่เลือก" style="width:90px;height:135px;object-fit:cover;border-radius:8px;margin-top:10px">
@if($record?->Poster)<img x-show="!preview" src="{{ $record->poster_url }}" alt="โปสเตอร์ปัจจุบัน" style="width:90px;height:135px;object-fit:cover;border-radius:8px;margin-top:10px">@endif
</div>
<label class="admin-full">เรื่องย่อ<textarea class="cinema-input" name="Synopsis" rows="3">{{ $restore ? old('Synopsis',$record?->Synopsis) : $record?->Synopsis }}</textarea></label>

