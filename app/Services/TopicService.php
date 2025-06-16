<?php

namespace App\Services;

use App\Models\Topic;

class TopicService
{
    public function create(array $data)
    {
        return Topic::create($data);
    }

    public function update($id, array $data)
    {
        $topic = Topic::findOrFail($id);
        $topic->update([
            'topic_name'=>$data['topic_name']
        ]);
        return $topic;
    }

    public function delete($id)
    {
        $topic = Topic::findOrFail($id);
        return $topic->delete();
    }
}
