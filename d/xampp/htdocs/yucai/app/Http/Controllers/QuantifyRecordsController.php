
public function archive(QuantifyRecord $quantify_record)
{
    $quantify_record->update(['is_archived' => true]);

    return redirect()->route('quantify_records.index')
                     ->with('message', 'Record archived successfully.');
}
