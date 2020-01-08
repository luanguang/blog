第一版本
```php
public function get(Application $app, Request $request, $fromId)
{
    $categoryIds = $request->query->get('categoryId');
    if (empty($categoryIds)) {
        return $this->error(403, '分类不能为空');
    }
    $userBind = $this->getUserService()->getUserBindByTypeAndFromId('tencent', $fromId);
    if (empty($userBind)) {
        return $this->error(404, '用户没有找到！');
    }

    $userId = $userBind['toId'];
    $categoryIds = explode(',', $categoryIds);//3,4,5

    $conditions['categoryIds'] = array();
    foreach ($categoryIds as $category) {
        $conditions['categoryIds'] = array_merge($conditions['categoryIds'], $this->getCategoryService()->findCategoryChildrenIds($category));
        $conditions['categoryIds'][] = $category; //定义一个service方法，传入categoryIds进行查询，不用循环查询
    }

    $courseMembers = $this->getCourseMemberService()->findStudentMemberByUserId($userId);//方法命名，可单独写一个方法
    $CourseSetIds = ArrayToolkit::column($courseMembers, 'courseSetId');
    $courseSets = $this->getCourseSetService()->findCourseSetsByIds($CourseSetIds);

    $learnedCourseSets = array_filter($courseSets, function ($courseSet) use ($conditions) {
        return array_intersect($courseSet['multiCategoryIds'], $conditions['categoryIds']);
    });

    $learnedCourseSetIds = ArrayToolkit::column($learnedCourseSets, 'id');
    $tasks = $this->getTaskService()->findTasksByCourseSetIds($learnedCourseSetIds);

    $groupTasks = ArrayToolkit::group($tasks, 'fromCourseSetId');
    $taskIds = ArrayToolkit::column($tasks, 'id');
    $taskResults = $this->getTaskResultService()->findUserTaskResultsByUserIdAndTaskIds($userId, $taskIds);
    $taskResults = ArrayToolkit::index($taskResults, 'courseTaskId');

    $results = array();
    foreach ($learnedCourseSets as $learnedCourseSet) {
        if (!isset($groupTasks[$learnedCourseSet['id']])) {
            continue;
        }
        $courseTasks = $groupTasks[$learnedCourseSet['id']];
        
        
        $courseTaskResults = array();
        foreach ($courseTasks as $courseTask) {
            $courseTaskResults[] = $taskResults[$courseTask['id']];
        }
        $finished = false;

        if (count($courseTasks) === count($courseTaskResults)) {
            $unfinished = array_filter($courseTaskResults, function ($result) {
                return 'finish' !== $result['status'];
            });

            if (empty($unfinished)) {
                $finished = true;
            }
        } else {
          continue;
        }
        $duration = 0;

        array_reduce($courseTaskResults, function ($k, $task) use (&$duration) {
            $duration += $task['time'];
        });

        $lastTime = $this->getViewLogService()->getUserCourseLastLearnTimeByUserIdAndCourse($userId, $learnedCourseSet['id']);

        $results[] = array(
            'CourseId' => $learnedCourseSet['defaultCourseId'],
            'Userid' => $fromId,
            'Duration' => $duration / 60,
            'LastTime' => date('Y-m-d H:i:s', $lastTime),
        );
    }
    if (empty($results)) {
        return array('code' => 200, 'message' => '该学员当前分类下还没有完成的课程');
    }

    return $results;
}
```

第二版本
```php
public function get(Application $app, Request $request, $fromId)
{
  $userBind = $this->getUserService()->getUserBindByTypeAndFromId('tencent', $fromId);
  $categoryIds = $request->query->get('categoryId');
  if (empty($categoryIds)) {
      return $this->error(403, '分类不能为空');
  }
  if (empty($userBind)) {
      return $this->error(404, '用户没有找到！');
  }

  $userId = $userBind['toId'];
  $categoryIds = explode(',', $categoryIds);

  $conditions['categoryIds'] = array();
  foreach ($categoryIds as $categoryId) {
      $conditions['categoryIds'] = array_merge($conditions['categoryIds'], $this->getCategoryService()->findCategoryChildrenIds($categoryId));
      $conditions['categoryIds'][] = $categoryId;
  }

  $courseMembers = $this->getCourseMemberService()->findStudentMemberByUserId($userId);
  $CourseSetIds = ArrayToolkit::column($courseMembers, 'courseSetId');
  $courseSets = $this->getCourseSetService()->findCourseSetsByIds($CourseSetIds);

  $learnCourseSets = array_filter($courseSets, function ($courseSet) use ($conditions) {
      return array_intersect($courseSet['multiCategoryIds'], $conditions['categoryIds']);
  });

  $learnCourseSetIds = ArrayToolkit::column($learnCourseSets, 'id');
  $tasks = $this->getTaskService()->findTasksByCourseSetIds($learnCourseSetIds);

  $groupTasks = ArrayToolkit::group($tasks, 'fromCourseSetId');
  $taskIds = ArrayToolkit::column($tasks, 'id');
  $taskResults = $this->getTaskResultService()->findUserTaskResultsByUserIdAndTaskIds($userId, $taskIds);
  $taskResults = ArrayToolkit::index($taskResults, 'courseTaskId');
  $viewLogs = $this->getViewLogService()->findViewLogByUserIdAndCourseIds($userId, $learnCourseSetIds);
  $viewLogs = ArrayToolkit::group($viewLogs, 'courseSetId');

  $results = array();
  foreach ($learnCourseSets as $learnCourseSet) {
      if (!isset($groupTasks[$learnCourseSet['id']])) {
          continue;
      }
      $courseTasks = $groupTasks[$learnCourseSet['id']];
      $duration = 0;
      $courseTaskResults = array();
      foreach ($courseTasks as $courseTask) {
          if (isset($taskResults[$courseTask['id']])) {
              $courseTaskResults[] = $taskResults[$courseTask['id']];
          }
      }

      $finished = false;
      if (count($courseTasks) === count($courseTaskResults)) {
          $unfinished = array_filter($courseTaskResults, function ($result) {
              return 'finish' !== $result['status'];
          });

          if (empty($unfinished)) {
              $finished = true;
          }
      }

      if (false == $finished) {
          continue;
      }

      array_reduce($courseTaskResults, function ($k, $task) use (&$duration) {
          $duration += $task['time'];
      });

      $courseViewLog = $viewLogs[$learnCourseSet['id']];
      $createdTime = array_column($courseViewLog, 'createdTime');
      array_multisort($createdTime, SORT_DESC, $courseViewLog);

      $results[] = array(
          'CourseId' => $learnCourseSet['defaultCourseId'],
          'Userid' => $fromId,
          'Duration' => $duration / 60,
          'LastTime' => date('Y-m-d H:i:s', $courseViewLog[0]['createdTime']),
      );
  }
  if (empty($results)) {
      return array('code' => 200, 'message' => '该学员当前分类下还没有完成的课程');
  }

  return $results;
}
```