<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZipArchive;


class oym extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =   'update:imprimir';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       
        $consectivos = array('1441',
'1442',
'1620',
'1963',
'1964',
'2484',
'2492',
'2496',
'2632',
'2633',
'2634',
'2635',
'2636',
'2637',
'3214',
'3467',
'3470',
'3969',
'4702',
'4720',
'4740',
'4741',
'4772',
'4780',
'4781',
'4856',
'4949',
'4950',
'4951',
'4958',
'4959',
'5149',
'5158',
'5177',
'5269',
'5271',
'5303',
'5304',
'5317',
'5318',
'5319',
'5320',
'5321',
'5322',
'5326',
'5329',
'5335',
'5337',
'5338',
'5340',
'5343',
'5351',
'5352',
'5353',
'5402',
'5412',
'5413',
'5430',
'5488',
'5489',
'5495',
'5504',
'5506',
'5508',
'5509',
'5511',
'5522',
'5523',
'5524',
'5525',
'5532',
'5533',
'5534',
'5538',
'5539',
'5540',
'5541',
'5542',
'5552',
'5553',
'5554',
'5555',
'5558',
'5559',
'5563',
'5570',
'5571',
'5572',
'5573',
'5576',
'5592',
'5596',
'5603',
'5647',
'5648',
'5649',
'5650',
'5651',
'5652',
'5653',
'5654',
'5655',
'5656',
'5657',
'5658',
'5659',
'5660',
'5661',
'5662',
'5663',
'5664',
'5665',
'5666',
'5667',
'5668',
'5669',
'5670',
'5671',
'5672',
'5673',
'5674',
'5675',
'5676',
'5677',
'5678',
'5679',
'5680',
'5681',
'5682',
'5683',
'5684',
'5685',
'5687',
'5689',
'5690',
'5694',
'5695',
'5696',
'5711',
'5712',
'5713',
'5715',
'5717',
'5718',
'5723',
'5725',
'5726',
'5727',
'5728',
'5729',
'5730',
'5732',
'5733',
'5735',
'5736',
'5737',
'5738',
'5740',
'5743',
'5744',
'5745',
'5746',
'5747',
'5749',
'5750',
'5751',
'5752',
'5753',
'5754',
'5755',
'5756',
'5757',
'5758',
'5759',
'5760',
'5761',
'5762',
'5763',
'5764',
'5765',
'5766',
'5768',
'5769',
'5770',
'5771',
'5773',
'5774',
'5775',
'5776',
'5777',
'5778',
'5779',
'5780',
'5781',
'5782',
'5784',
'5785',
'5787',
'5788',
'5790',
'5792',
'5793',
'5794',
'5795',
'5796',
'5797',
'5798',
'5799',
'5800',
'5801',
'5802',
'5803',
'5804',
'5805',
'5806',
'5807',
'5808',
'5809',
'5810',
'5811',
'5812',
'5813',
'5814',
'5815',
'5816',
'5817',
'5818',
'5819',
'5820',
'5821',
'5822',
'5823',
'5824',
'5825',
'5826',
'5827',
'5828',
'5829',
'5830',
'5831',
'5832',
'5833',
'5834',
'5835',
'5836',
'5837',
'5838',
'5839',
'5841',
'5843',
'5844',
'5845',
'5846',
'5847',
'5850',
'5851',
'5852',
'5853',
'5854',
'5855',
'5856',
'5858',
'5859',
'5860',
'5861',
'5862',
'5863',
'5865',
'5866',
'5869',
'5871',
'5872',
'5873',
'5874',
'5875',
'5876',
'5877',
'5878',
'5879',
'5880',
'5881',
'5882',
'5883',
'5884',
'5885',
'5886',
'5887',
'5888',
'5889',
'5890',
'5891',
'5892',
'5893',
'5894',
'5895',
'5897',
'5898',
'5899',
'5900',
'5901',
'5902',
'5903',
'5904',
'5905',
'5906',
'5907',
'5908',
'5910',
'5911',
'5912',
'5914',
'5915',
'5916',
'5917',
'5918',
'5922',
'5923',
'5924',
'5925',
'5927',
'5928',
'5929',
'5930',
'5931',
'5932',
'5933',
'5934',
'5935',
'5936',
'5937',
'5938',
'5939',
'5940',
'5942',
'5943',
'5944',
'5945',
'5946',
'5947',
'5948',
'5949',
'5950',
'5951',
'5952',
'5953',
'5954',
'5955',
'5956',
'5957',
'5958',
'5960',
'5961',
'5962',
'5965',
'5966',
'5967',
'5968',
'5969',
'5970',
'5971',
'5972',
'5973',
'5974',
'5975',
'5976',
'5977',
'5979',
'5980',
'5981',
'5982',
'5983',
'5984',
'5985',
'5986',
'5987',
'5989',
'5990',
'5991',
'5992',
'5993',
'5994',
'5995',
'5996',
'5997',
'5998',
'5999',
'6000',
'6001',
'6002',
'6003',
'6004',
'6005',
'6006',
'6007',
'6008',
'6009',
'6010',
'6011',
'6012',
'6013',
'6014',
'6015',
'6016',
'6017',
'6018',
'6019',
'6020',
'6021',
'6022',
'6023',
'6024',
'6025',
'6026',
'6027',
'6028',
'6029',
'6030',
'6031',
'6032',
'6034',
'6035',
'6036',
'6037',
'6038',
'6039',
'6040',
'6042',
'6044',
'6045',
'6046',
'6048',
'6050',
'6054',
'6055',
'6056',
'6059',
'6060',
'6061',
'6062',
'6063',
'6064',
'6067',
'6068',
'6070',
'6071',
'6072',
'6073',
'6074',
'6075',
'6076',
'6077',
'6078',
'6079',
'6080',
'6081',
'6083',
'6084',
'6086',
'6087',
'6088',
'6089',
'6090',
'6091',
'6092',
'6093',
'6096',
'6097',
'6098',
'6099',
'6100',
'6101',
'6102',
'6103',
'6104',
'6105',
'6106',
'6107',
'6108',
'6112',
'6113',
'6114',
'6115',
'6117',
'6118',
'6119',
'6120',
'6122',
'6123',
'6126',
'6127',
'6128',
'6129',
'6130',
'6131',
'6132',
'6133',
'6134',
'6135',
'6137',
'6138',
'6139',
'6140',
'6141',
'6142',
'6143',
'6144',
'6145',
'6146',
'6147',
'6148',
'6149',
'6150',
'6151',
'6152',
'6153',
'6154',
'6155',
'6156',
'6157',
'6158',
'6159',
'6160',
'6161',
'6162',
'6163',
'6164',
'6165',
'6167',
'6168',
'6169',
'6170',
'6171',
'6172',
'6173',
'6174',
'6175',
'6176',
'6177',
'6178',
'6179',
'6180',
'6181',
'6182',
'6183',
'6184',
'6185',
'6186',
'6187',
'6188',
'6189',
'6190',
'6191',
'6192',
'6193',
'6194',
'6195',
'6196',
'6197',
'6198',
'6199',
'6200',
'6201',
'6202',
'6203',
'6204',
'6205',
'6206',
'6207',
'6208',
'6209',
'6210',
'6211',
'6212',
'6213',
'6214',
'6215',
'6216',
'6217',
'6220',
'6221',
'6222',
'6223',
'6224',
'6225',
'6226',
'6227',
'6229',
'6230',
'6231',
'6232',
'6233',
'6234',
'6235',
'6236',
'6237',
'6238',
'6239',
'6240',
'6241',
'6242',
'6243',
'6244',
'6245',
'6246',
'6247',
'6249',
'6251',
'6252',
'6253',
'6255',
'6256',
'6257',
'6258',
'6260',
'6261',
'6262',
'6263',
'6264',
'6265',
'6266',
'6269',
'6270',
'6271',
'6272',
'6273',
'6274',
'6275',
'6277',
'6278',
'6279',
'6280',
'6281',
'6282',
'6284',
'6285',
'6286',
'6287',
'6288',
'6289',
'6290',
'6291',
'6292',
'6293',
'6294',
'6295',
'6296',
'6297',
'6298',
'6299',
'6300',
'6301',
'6302',
'6303',
'6304',
'6305',
'6306',
'6307',
'6308',
'6309',
'6310',
'6311',
'6312',
'6313',
'6314',
'6318',
'6319',
'6320',
'6321',
'6322',
'6323',
'6324',
'6325',
'6326',
'6327',
'6328',
'6329',
'6330',
'6331',
'6332',
'6333',
'6334',
'6335',
'6336',
'6337',
'6338',
'6339',
'6340',
'6341',
'6342',
'6343',
'6344',
'6345',
'6346',
'6347',
'6348',
'6349',
'6350',
'6352',
'6353',
'6355',
'6356',
'6357',
'6359',
'6360',
'6361',
'6362',
'6363',
'6364',
'6365',
'6366',
'6367',
'6368',
'6370',
'6371',
'6372',
'6373',
'6376',
'6377',
'6378',
'6379',
'6380',
'6381',
'6382',
'6383',
'6384',
'6385',
'6386',
'6387',
'6389',
'6390',
'6391',
'6392',
'6393',
'6394',
'6395',
'6396',
'6400',
'6401',
'6402',
'6403',
'6404',
'6405',
'6406',
'6407',
'6408',
'6409',
'6410',
'6412',
'6419',
'6420',
'6423',
'6424',
'6425',
'6426',
'6427',
'6430',
'6431',
'6432',
'6433',
'6434',
'6435',
'6436',
'6437',
'6438',
'6439',
'6440',
'6441',
'6442',
'6443',
'6444',
'6446',
'6448',
'6449',
'6450',
'6451',
'6452',
'6455',
'6456',
'6457',
'6458',
'6459',
'6460',
'6461',
'6462',
'6464',
'6465',
'6466',
'6467',
'6468',
'6469',
'6470',
'6471',
'6472',
'6473',
'6474',
'6475',
'6476',
'6477',
'6478',
'6479',
'6480',
'6481',
'6482',
'6483',
'6484',
'6485',
'6486',
'6487',
'6488',
'6489',
'6490',
'6491',
'6492',
'6493',
'6494',
'6495',
'6496',
'6497',
'6498',
'6499',
'6500',
'6501',
'6502',
'6503',
'6504',
'6505',
'6506',
'6507',
'6508',
'6511',
'6512',
'6513',
'6514',
'6515',
'6516',
'6517',
'6518',
'6519',
'6520',
'6521',
'6522',
'6523',
'6524',
'6525',
'6526',
'6527',
'6528',
'6529',
'6530',
'6531',
'6532',
'6533',
'6534',
'6535',
'6536',
'6537',
'6538',
'6539',
'6540',
'6541',
'6542',
'6543',
'6544',
'6545',
'6546',
'6547',
'6548',
'6549',
'6550',
'6551',
'6552',
'6553',
'6554',
'6555',
'6556',
'6557',
'6558',
'6559',
'6560',
'6561',
'6562',
'6563',
'6564',
'6565',
'6566',
'6567',
'6568',
'6570',
'6572',
'6573',
'6574',
'6575',
'6576',
'6577',
'6579',
'6581',
'6582',
'6583',
'6584',
'6585',
'6586',
'6587',
'6711',
'6712',
'6717',
'6718',
'6719',
'6720',
'6726',
'6727',
'6728',
'6729',
'6730',
'6733',
'6734',
'6735',
'6736',
'6744',
'6746',
'6747',
'6748',
'6752',
'6753',
'6754',
'6755',
'6756',
'6757',
'6758',
'6759',
'6760',
'6761',
'6762',
'6763',
'6764',
'6765',
'6766',
'6767',
'6768',
'6769',
'6770',
'6771',
'6772',
'6773',
'6774',
'6775',
'6776',
'6777',
'6778',
'6779',
'6780',
'6781',
'6782',
'6783',
'6784',
'6785',
'6786',
'6787',
'6788',
'6789',
'6791',
'6792',
'6793',
'6794',
'6795',
'6798',
'6799',
'6800',
'6801',
'6802',
'6803',
'6804',
'6805',
'6806',
'6808',
'6809',
'6811',
'6812',
'6813',
'6814',
'6815',
'6816',
'6817',
'6818',
'6819',
'6820',
'6821',
'6822',
'6823',
'6824',
'6825',
'6826',
'6827',
'6828',
'6829',
'6830',
'6831',
'6832',
'6833',
'6834',
'6835',
'6836',
'6837',
'6839',
'6840',
'6841',
'6842',
'6843',
'6844',
'6845',
'6847',
'6848',
'6849',
'6851',
'6852',
'6853',
'6854',
'6855',
'6857',
'6858',
'6859',
'6860',
'6861',
'6862',
'6863',
'6865',
'6866',
'6867',
'6868',
'6869',
'6871',
'6872',
'6873',
'6877',
'6878',
'6879',
'6880',
'6881',
'6882',
'6883',
'6884',
'6885',
'6886',
'6887',
'6888',
'6889',
'6891',
'6892',
'6893',
'6896',
'6897',
'6898',
'6899',
'6900',
'6901',
'6903',
'6904',
'6911',
'6912',
'6914',
'6915',
'6916',
'6918',
'6944',
'6945',
'6946',
'6947',
'6948',
'6950',
'6951',
'6952',
'6953',
'6955',
'6956',
'6957',
'6958',
'6959',
'6961',
'6963',
'6964',
'6965',
'6966',
'6968',
'6969',
'6970',
'6971',
'6972',
'6973',
'6974',
'6975',
'6976',
'6978',
'6979',
'6986',
'6987',
'6991',
'6992',
'6994',
'6996',
'6999',
'7000',
'7003',
'7004',
'7005',
'7006',
'7007',
'7009',
'7010',
'7011',
'7012',
'7013',
'7014',
'7015',
'7016',
'7017',
'7018',
'7019',
'7020',
'7021',
'7022',
'7023',
'7024',
'7030',
'7048',
'7049',
'7051',
'7054',
'7057',
'7058',
'7059',
'7060',
'7061',
'7062',
'7063',
'7066',
'7068',
'7070',
'7073',
'7074',
'7078',
'7080',
'7081',
'7082',
'7083',
'7084',
'7085',
'7086',
'7087',
'7088',
'7089',
'7092',
'7093',
'7094',
'7096',
'7097',
'7099',
'7100',
'7101',
'7102',
'7103',
'7104',
'7105',
'7106',
'7109',
'7110',
'7118',
'7120',
'7121',
'7125',
'7126',
'7127',
'7128',
'7129',
'7132',
'7133',
'7137',
'7138',
'7139',
'7140',
'7141',
'7147',
'7148',
'7149',
'7150',
'7151',
'7152',
'7153',
'7154',
'7155',
'7156',
'7157',
'7158',
'7159',
'7160',
'7161',
'7162',
'7163',
'7164',
'7165',
'7166',
'7167',
'7168',
'7169',
'7170',
'7171',
'7172',
'7173',
'7174',
'7175',
'7176',
'7178',
'7179',
'7180',
'7182',
'7183',
'7184',
'7185',
'7186',
'7187',
'7189',
'7190',
'7191',
'7192',
'7193',
'7194',
'7195',
'7196',
'7197',
'7198',
'7199',
'7200',
'7201',
'7202',
'7204',
'7205',
'7206',
'7207',
'7208',
'7209',
'7210',
'7211',
'7212',
'7213',
'7214',
'7215',
'7216',
'7217',
'7218',
'7219',
'7220',
'7221',
'7224',
'7226',
'7227',
'7228',
'7229',
'7231',
'7232',
'7234',
'7236',
'7237',
'7238',
'7239',
'7240',
'7241',
'7242',
'7243',
'7244',
'7245',
'7251',
'7252',
'7253',
'7254',
'7255',
'7256',
'7257',
'7258',
'7259',
'7260',
'7261',
'7263',
'7264',
'7271',
'7273',
'7274',
'7275',
'7276',
'7277',
'7278',
'7279',
'7280',
'7281',
'7282',
'7283',
'7284',
'7285',
'7286',
'7287',
'7288',
'7289',
'7292',
'7293',
'7294',
'7297',
'7298',
'7299',
'7304',
'7306',
'7308',
'7309',
'7310',
'7311',
'7312',
'7314',
'7315',
'7316',
'7317',
'7319',
'7323',
'7324',
'7325',
'7326',
'7327',
'7328',
'7329',
'7330',
'7332',
'7333',
'7335',
'7336',
'7338',
'7339',
'7341',
'7343',
'7344',
'7345',
'7346',
'7348',
'7349',
'7351',
'7352',
'7353',
'7354',
'7355',
'7356',
'7359',
'7377',
'7382',
'7386',
'7387',
'7388',
'7389',
'7391',
'7399',
'7400',
'7401',
'7402',
'7404',
'7409',
'7412',
'7413',
'7414',
'7415',
'7416',
'7417',
'7419',
'7421',
'7422',
'7423',
'7427',
'7428',
'7429',
'7430',
'7431',
'7435',
'7441',
'7442',
'7443',
'7444',
'7446',
'7447',
'7448',
'7449',
'7450',
'7451',
'7452',
'7453',
'7455',
'7456',
'7458',
'7461',
'7464',
'7467',
'7468',
'7470',
'7471',
'7472',
'7473',
'7474',
'7477',
'7478',
'7480',
'7481',
'7482',
'7483',
'7484',
'7485',
'7486',
'7487',
'7488',
'7490',
'7491',
'7492',
'7493',
'7494',
'7495',
'7497',
'7498',
'7499',
'7501',
'7502',
'7503',
'7504',
'7505',
'7506',
'7507',
'7508',
'7509',
'7510',
'7511',
'7514',
'7516',
'7518',
'7519',
'7520',
'7521',
'7522',
'7523',
'7524',
'7526',
'7527',
'7528',
'7529',
'7530',
'7531',
'7532',
'7533',
'7534',
'7535',
'7536',
'7537',
'7539',
'7540',
'7541',
'7542',
'7543',
'7544',
'7545',
'7546',
'7547',
'7548',
'7550',
'7551',
'7555',
'7556',
'7557',
'7559',
'7561',
'7562',
'7563',
'7564',
'7565',
'7566',
'7567',
'7569',
'7570',
'7571',
'7572',
'7574',
'7575',
'7576',
'7577',
'7579',
'7580',
'7581',
'7582',
'7583',
'7584',
'7585',
'7586',
'7587',
'7588',
'7589',
'7590',
'7591',
'7592',
'7593',
'7594',
'7595',
'7596',
'7597',
'7598',
'7600',
'7601',
'7604',
'7606',
'7609',
'7611',
'7613',
'7614',
'7616',
'7617',
'7621',
'7625',
'7626',
'7629',
'7630',
'7634',
'7638',
'7645',
'7658',
'7660',
'7662',
'7666',
'7667',
'7669',
'7672',
'7673',
'7675',
'7678',
'7680',
'7681',
'7682',
'7683',
'7702',
'7721',
'7732',
'7734',
'7742',
'7770',
'7795',
'7801');

foreach ($consectivos as $key => $consectivo) {

    echo $consectivo;

    $search=DB::table('images_oym')
    ->where('id_oym',$consectivo)
    ->get();


    $search_oym=DB::table('oym')
    ->where('consecutive',$consectivo)
    ->first();

    $activity = DB::table('list_activity_oym')
    ->where('id_activity', $search_oym->activity)
    ->first();

    $path = public_path('/public/acta');

    $zipFileName = $search_oym->pedido.'-'.$search_oym->ot.'-'.$search_oym->cod_instalacion.'-'. $activity->name_activity. '.zip';
    $zip         = new ZipArchive;

    if ($zip->open($path . '/' . $zipFileName, ZipArchive::CREATE) === true) {
        // Add File in ZipArchive

        foreach ($search as $search) {
            $path1 = public_path('/public' . $search->url . $search->name_image);

            $zip->addFile($path1, $search->name_image);
        }
        $zip->close();
    }
    $headers = array(
        'Pragma'                    => 'public',
        'Expires'                   => '0',
        'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
        'Cache-Control'             => 'public',
        'Content-Description'       => 'File Transfer',
        'Content-type'              => 'application/octet-stream',
        'Content-Transfer-Encoding' => 'binary',

    );

    $filetopath = $path . '/public/acta' . $zipFileName;
}
        //
    }
}
