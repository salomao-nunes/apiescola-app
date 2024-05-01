<?php

namespace App\Http\Controllers;

use App\Imports\EscolaImport;
use App\Models\Escola;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

/**
* @OA\Info(
*             title="API DE ESCOLAS", 
*             version="1.0",
*             description="Esta api permite gerenciar informações sobre escolas."
* )
*
* @OA\Server(url="http://localhost:8000/api")
*/

class EscolaController extends Controller
{
    //

     /**
     * @OA\Get(
     *     path="/escolas",
     *     summary="Lista todas as escolas",
     *     tags={"Escolas"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de escolas",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function index()
    {
        $escolas = Escola::all();
        return response()->json($escolas);
    }

    /**
 * @OA\Post(
 *     path="/cadastrar-escola",
 *     summary="Cadastra uma nova escola",
 *     tags={"Escolas"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="nome", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="numero_de_salas", type="integer"),
 *             @OA\Property(property="provincia", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Escola cadastrada com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", format="int64", description="ID da escola"),
 *             @OA\Property(property="nome", type="string", description="Nome da escola"),
 *             @OA\Property(property="email", type="string", format="email", description="Email da escola"),
 *             @OA\Property(property="numero_de_salas", type="integer", description="Número de salas da escola"),
 *             @OA\Property(property="provincia", type="string", description="Província da escola")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação",
 *         @OA\JsonContent()
 *     ),
 * )
 */

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email',
            'numero_de_salas' => 'required|integer',
            'provincia' => 'required',
        ]);

        $escola = Escola::create($request->all());

        return response()->json($escola, 201);
    }


/**
     * @OA\Get(
     *     path="/escola/{id}",
     *     summary="Exibe os detalhes de uma escola",
     *     tags={"Escolas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da escola",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da escola",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Escola não encontrada",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function show($id)
    {
        $escola = Escola::findOrFail($id);
        return response()->json($escola);
    }

/**
 * @OA\Put(
 *     path="/escola/{id}",
 *     summary="Atualiza os detalhes de uma escola",
 *     tags={"Escolas"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID da escola",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="nome", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="numero_de_salas", type="integer"),
 *             @OA\Property(property="provincia", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalhes da escola atualizados com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", format="int64", description="ID da escola"),
 *             @OA\Property(property="nome", type="string", description="Nome da escola"),
 *             @OA\Property(property="email", type="string", format="email", description="Email da escola"),
 *             @OA\Property(property="numero_de_salas", type="integer", description="Número de salas da escola"),
 *             @OA\Property(property="provincia", type="string", description="Província da escola")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Escola não encontrada",
 *         @OA\JsonContent()
 *     ),
 * )
 */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email',
            'numero_de_salas' => 'required|integer',
            'provincia' => 'required',
        ]);

        $escola = Escola::findOrFail($id);
        $escola->update($request->all());
        return response()->json($escola, 200);
    }

    /**
     * @OA\Delete(
     *     path="/escola/{id}",
     *     summary="Remove uma escola",
     *     tags={"Escolas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da escola",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Escola removida com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Escola não encontrada",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function destroy($id)
    {
        $escola = Escola::findOrFail($id);
        $escola->delete();
        return response()->json(null, 204);
    }


    /**
     * @OA\Post(
     *     path="/escolas/upload-excell",
     *     summary="Faz o upload de um arquivo Excel para importação de escolas",
     *     tags={"Escolas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="file",
     *                     description="Arquivo Excel para importação",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dados da escola importados com sucesso",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function uploadXLSX(Request $request)
    {

        // Validação dos dados do arquivo Excel
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Processamento do arquivo Excel
        $file = $request->file('file');

        $dados = Excel::toCollection(new EscolaImport, $file)->first();

        // dd($dados);

        $firstRow = true;

        foreach ($dados as $row) {
            if ($firstRow) {
                $firstRow = false;
                continue;
            }
        
            Escola::create([
                'nome' => $row[0],
                'email' => $row[1],
                'numero_de_salas' => $row[2],
                'provincia' => $row[3],
            ]);
        }


        return response()->json(['message' => 'Dados da escola importados com sucesso.']);
    }


    public function provinces(){
        // Ler o conteúdo do JSON
        $jsonContent = Storage::disk('public')->get('province.json');

        // Decodificar o JSON para array
        $provincias = json_decode($jsonContent, true);

        // Retornar os dados como resposta JSON
        return response()->json($provincias);
    }
}
