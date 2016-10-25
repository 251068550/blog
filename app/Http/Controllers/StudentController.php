<?php
/**
 * Created by PhpStorm.
 * User: zxd251068550
 * Date: 2016/10/24
 * Time: 21:51
 */

namespace App\Http\Controllers;


use App\Student;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class StudentController extends Controller
{
    //学生列表页
    public function index()
    {
//       $num =  Student::saved([
//            ['name' => '姓名1', 'age' => 11, 'sex' => 11],
//            ['name' => '姓名2', 'age' => 12, 'sex' => 12],
//            ['name' => '姓名3', 'age' => 13, 'sex' => 13],
//            ['name' => '姓名4', 'age' => 14, 'sex' => 14],
//            ['name' => '姓名5', 'age' => 15, 'sex' => 15],
//            ['name' => '姓名6', 'age' => 16, 'sex' => 16],
//            ['name' => '姓名7', 'age' => 17, 'sex' => 17]
//        ]);
//
//        var_dump($num);

        $students = Student::paginate(10);

        return view('student.index', [
            'students' => $students
        ]);
    }

    //添加页面
    public function create(Request $request){

        $student = new Student();

        //保存数据
        if ($request->isMethod('post')) {

            //1.控制器验证(推荐，已经自动把错误信息放入session中，并且数据保持)
           /* $this->validate($request, [
                'Student.name' => 'required|min:2|max:20',
                'Student.age' => 'required|integer',
                'Student.sex' => 'required|integer',
            ],[
                'required' => ':attribute 为必填项',
                'min' => ':attribute 长度不符合要求',
                'integer' => ':attribute 必须为整数',
            ], [
                'Student.name' => '姓名',
                'Student.age' => '年龄',
                'Student.sex' => '性别'
            ]);*/

            //2.Validator验证
           $validator = \Validator::make($request->input(), [
                'Student.name' => 'required|min:2|max:20',
                'Student.age' => 'required|integer',
                'Student.sex' => 'required|integer',
            ],[
                'required' => ':attribute 为必填项',
                'min' => ':attribute 长度不符合要求',
                'integer' => ':attribute 必须为整数',
            ], [
                'Student.name' => '姓名',
                'Student.age' => '年龄',
                'Student.sex' => '性别'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $request->input('Student');
            if (Student::create($data)) {
                return redirect('student/index')->with('success', '添加成功');
            } else {
                return redirect()->back();
            }
        }
        //跳转到新增页面
        return view('student.create', [
            'student' => $student
        ]);
    }

    //保存添加
    public function save(Request $request){
        $data = $request->input('Student');

        $student = new Student();
        $student->name = $data['name'];
        $student->age = $data['age'];
        $student->sex = $data['sex'];

        if ($student->save()) {
            return redirect('student/index');
        } else {
            return redirect()->back();
        }
    }

    //修改(request参数不占用参数的位置，所以id还是在第一位)
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if ($request->isMethod('POST')) {

            $this->validate($request, [
                'Student.name' => 'required|min:2|max:20',
                'Student.age' => 'required|integer',
                'Student.sex' => 'required|integer',
            ],[
                'required' => ':attribute 为必填项',
                'min' => ':attribute 长度不符合要求',
                'integer' => ':attribute 必须为整数',
            ], [
                'Student.name' => '姓名',
                'Student.age' => '年龄',
                'Student.sex' => '性别'
            ]);

            $data = $request->input('Student');
            $student->name = $data['name'];
            $student->age = $data['age'];
            $student->sex = $data['sex'];

            if ($student->save()) {
                return redirect('student/index')->with('success', '修改成功-' . $id);
            }
        }

        return view('student.update',[
            'student' => $student
        ]);
    }

    //详情
    public function detail($id)
    {
        $student = Student::find($id);
        return view('student.detail',[
            'student' => $student
        ]);
    }

    //删除
    public function delete($id)
    {
        $student = Student::find($id);
        if ($student->delete()){
            return redirect('student/index')->with('success', '删除成功-' . $id);
        } else {
            return redirect('student/index')->with('error', '删除失败-' . $id);
        }
    }
}